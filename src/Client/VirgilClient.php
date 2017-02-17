<?php
namespace Virgil\Sdk\Client;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Requests\PublishGlobalCardRequest;
use Virgil\Sdk\Client\Requests\RevokeGlobalCardRequest;
use Virgil\Sdk\Client\Requests\SearchCardRequest;
use Virgil\Sdk\Client\Requests\CreateCardRequest;
use Virgil\Sdk\Client\Requests\RevokeCardRequest;

use Virgil\Sdk\Client\Validator\CardValidationException;
use Virgil\Sdk\Client\Validator\CardValidatorInterface;

use Virgil\Sdk\Client\Http\Curl\CurlClient;
use Virgil\Sdk\Client\Http\Curl\CurlRequestFactory;

use Virgil\Sdk\Client\VirgilServices\Http\HttpClient;

use Virgil\Sdk\Client\VirgilServices\Mapper\CardContentModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedResponseModelsMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedRequestModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedResponseModelMapper;
use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseModel;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsServiceParams;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsService;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsServiceInterface;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\ErrorResponseModelMapper as VirgilCardsErrorResponseModelMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\ModelMappersCollection as VirgilCardsMapperModelMappersCollection;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\SearchRequestModelMapper;

use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Mapper\ErrorResponseModelMapper as RegistrationAuthorityErrorResponseModelMapper;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Mapper\ModelMappersCollection as RegistrationAuthorityModelMappersCollection;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityService;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityServiceInterface;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityServiceParams;

/**
 * Before you can use any Virgil services features in your app, you must first initialize VirgilClient class.
 * You use the VirgilClient object to get access to Create, Revoke and Search for Virgil Cards (Public keys).
 */
class VirgilClient
{
    const AUTH_HEADER_FORMAT = 'VIRGIL %s';

    /** @var CardsServiceInterface */
    private $cardsService;

    /** @var RegistrationAuthorityServiceInterface */
    private $registrationAuthorityService;

    /** @var CardValidatorInterface */
    private $cardValidator;


    /**
     * Class constructor.
     *
     * @param VirgilClientParamsInterface           $virgilClientParams
     * @param CardsServiceInterface                 $cardsService
     * @param RegistrationAuthorityServiceInterface $registrationAuthorityService
     */
    public function __construct(
        VirgilClientParamsInterface $virgilClientParams,
        CardsServiceInterface $cardsService = null,
        RegistrationAuthorityServiceInterface $registrationAuthorityService = null
    ) {
        if ($cardsService === null) {
            $cardsService = $this->initializeCardService($virgilClientParams);
        }

        if ($registrationAuthorityService === null) {
            $registrationAuthorityService = $this->initializeRegistrationAuthorityService($virgilClientParams);
        }

        $this->cardsService = $cardsService;
        $this->registrationAuthorityService = $registrationAuthorityService;
    }


    /**
     * Makes client by provided access token.
     *
     * @param string $accessToken
     *
     * @return VirgilClient
     */
    public static function create($accessToken)
    {
        return new self(new VirgilClientParams($accessToken));
    }


    /**
     * Performs the Virgil Cards service searching by search request.
     *
     * @param SearchCardRequest $searchCardRequest
     *
     * @return Card[]
     */
    public function searchCards(SearchCardRequest $searchCardRequest)
    {
        $response = $this->cardsService->search($searchCardRequest->getRequestModel());

        $responseModelToCard = function (SignedResponseModel $responseModel) {
            return $this->buildAndVerifyCard($responseModel);
        };

        return array_map($responseModelToCard, $response);
    }


    /**
     * Performs the Virgil Cards service card creation by request.
     *
     * @param CreateCardRequest $createCardRequest
     *
     * @return Card
     */
    public function createCard(CreateCardRequest $createCardRequest)
    {
        $response = $this->cardsService->create($createCardRequest->getRequestModel());

        return $this->buildAndVerifyCard($response);
    }


    /**
     * Performs the Virgil Cards service card revoking by request.
     *
     * @param RevokeCardRequest $revokeCardRequest
     *
     * @return $this
     */
    public function revokeCard(RevokeCardRequest $revokeCardRequest)
    {
        $this->cardsService->delete($revokeCardRequest->getRequestModel());

        return $this;
    }


    /**
     * Performs the Virgil Cards service card searching by ID.
     *
     * @param $id
     *
     * @return Card
     */
    public function getCard($id)
    {
        $response = $this->cardsService->get($id);

        return $this->buildAndVerifyCard($response);
    }


    /**
     * Sets the card validator.
     *
     * @param CardValidatorInterface $validator
     *
     * @return $this
     */
    public function setCardValidator(CardValidatorInterface $validator)
    {
        $this->cardValidator = $validator;

        return $this;
    }


    /**
     * Performs the Virgil RA service global card creation by request.
     *
     * @param PublishGlobalCardRequest $publishGlobalCardRequest
     *
     * @return Card
     */
    public function publishGlobalCard(PublishGlobalCardRequest $publishGlobalCardRequest)
    {
        $response = $this->registrationAuthorityService->create($publishGlobalCardRequest->getRequestModel());

        return $this->buildAndVerifyCard($response);
    }


    /**
     * Performs the Virgil RA global card revoking by request.
     *
     * @param RevokeGlobalCardRequest $revokeGlobalCardRequest
     *
     * @return $this
     */
    public function revokeGlobalCard(RevokeGlobalCardRequest $revokeGlobalCardRequest)
    {
        $this->registrationAuthorityService->delete($revokeGlobalCardRequest->getRequestModel());

        return $this;
    }


    /**
     * Builds card from response model.
     *
     * @param SignedResponseModel $responseModel
     *
     * @return Card
     */
    private function responseToCard(SignedResponseModel $responseModel)
    {
        $responseCardModelContent = $responseModel->getCardContent();
        $responseCardModelContentInfo = $responseCardModelContent->getInfo();
        $responseCardModelMeta = $responseModel->getMeta();

        $responseModelSignsToCardSigns = function ($sign) {
            return Buffer::fromBase64($sign);
        };

        $cardSigns = array_map($responseModelSignsToCardSigns, $responseCardModelMeta->getSigns());

        return new Card(
            $responseModel->getId(),
            Buffer::fromBase64($responseModel->getSnapshot()),
            $responseCardModelContent->getIdentity(),
            $responseCardModelContent->getIdentityType(),
            Buffer::fromBase64($responseCardModelContent->getPublicKey()),
            $responseCardModelContent->getScope(),
            $responseCardModelContent->getData(),
            $responseCardModelContentInfo->getDevice(),
            $responseCardModelContentInfo->getDeviceName(),
            $responseCardModelMeta->getCardVersion(),
            $cardSigns
        );
    }


    /**
     * Validate card.
     *
     * @param Card $card
     *
     * @return $this
     *
     * @throws CardValidationException
     */
    private function validateCard(Card $card)
    {
        if ($this->cardValidator != null) {
            $this->cardValidator->validate($card);
        }

        return $this;
    }


    /**
     * Builds and verify card from response model.
     *
     * @param SignedResponseModel $responseModel
     *
     * @return Card
     *
     * @throws CardValidationException
     */
    private function buildAndVerifyCard(SignedResponseModel $responseModel)
    {
        $card = $this->responseToCard($responseModel);
        $this->validateCard($card);

        return $card;
    }


    /**
     * Initialize default card service.
     *
     * @param VirgilClientParamsInterface $virgilClientParams
     *
     * @return CardsServiceInterface
     */
    private function initializeCardService(VirgilClientParamsInterface $virgilClientParams)
    {
        $immutableHost = $virgilClientParams->getReadOnlyCardsServiceAddress();
        $mutableHost = $virgilClientParams->getCardsServiceAddress();

        $cardsServiceParams = new CardsServiceParams($immutableHost, $mutableHost);

        $curlRequestFactory = new CurlRequestFactory([CURLOPT_RETURNTRANSFER => 1, CURLOPT_HEADER => true]);

        $httpHeaders = [
            'Authorization' => sprintf(self::AUTH_HEADER_FORMAT, $virgilClientParams->getAccessToken()),
        ];

        $curlClient = new CurlClient($curlRequestFactory, $httpHeaders);

        $signedResponseModelMapper = new SignedResponseModelMapper(new CardContentModelMapper());

        $jsonMappers = new VirgilCardsMapperModelMappersCollection(
            $signedResponseModelMapper,
            new SignedRequestModelMapper(),
            new SignedResponseModelsMapper($signedResponseModelMapper),
            new SearchRequestModelMapper(),
            new VirgilCardsErrorResponseModelMapper()
        );

        $virgilServicesHttpClient = new HttpClient($curlClient, $jsonMappers->getErrorResponseModelMapper());

        return new CardsService($cardsServiceParams, $virgilServicesHttpClient, $jsonMappers);
    }


    /**
     * Initialize default registration authority service.
     *
     * @param VirgilClientParamsInterface $virgilClientParams
     *
     * @return RegistrationAuthorityServiceInterface
     */
    private function initializeRegistrationAuthorityService($virgilClientParams)
    {
        $registrationAuthorityServiceHost = $virgilClientParams->getRegistrationAuthorityServiceAddress();

        $registrationAuthorityServiceParams = new RegistrationAuthorityServiceParams($registrationAuthorityServiceHost);

        $curlRequestFactory = new CurlRequestFactory([CURLOPT_RETURNTRANSFER => 1, CURLOPT_HEADER => true]);

        $curlClient = new CurlClient($curlRequestFactory);

        $jsonMappers = new RegistrationAuthorityModelMappersCollection(
            new SignedResponseModelMapper(new CardContentModelMapper()),
            new SignedRequestModelMapper(),
            new RegistrationAuthorityErrorResponseModelMapper()
        );

        $virgilServicesHttpClient = new HttpClient($curlClient, $jsonMappers->getErrorResponseModelMapper());

        return new RegistrationAuthorityService(
            $registrationAuthorityServiceParams, $virgilServicesHttpClient, $jsonMappers
        );
    }
}
