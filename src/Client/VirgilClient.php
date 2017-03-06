<?php
namespace Virgil\Sdk\Client;


use Virgil\Sdk\Client\Card\CardMapperInterface;
use Virgil\Sdk\Client\Card\SignedResponseCardMapper;

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

use Virgil\Sdk\Client\VirgilServices\Model\SignedResponseModel;

use Virgil\Sdk\Client\VirgilServices\UnsuccessfulResponseException;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsServiceParams;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsService;
use Virgil\Sdk\Client\VirgilServices\VirgilCards\CardsServiceInterface;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\ModelMappersCollection as VirgilCardsMapperModelMappersCollection;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\IdentityService;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\IdentityServiceInterface;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\IdentityServiceParams;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\ConfirmRequestModel;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\TokenModel;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\ValidateRequestModel;
use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model\VerifyRequestModel;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Mapper\ModelMappersCollection as IdentityModelMappersCollection;

use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityService;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityServiceInterface;
use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\RegistrationAuthorityServiceParams;

use Virgil\Sdk\Client\VirgilServices\VirgilRegistrationAuthority\Mapper\ModelMappersCollection as RegistrationAuthorityModelMappersCollection;

/**
 * Before you can use any Virgil services features in your app, you must first initialize VirgilClient class.
 * You use the VirgilClient object to get access to Create, Revoke and Search for Virgil Cards (Public keys).
 * Use appropriate methods to verify user identity if needed.
 */
class VirgilClient implements VirgilClientInterface
{
    const AUTH_HEADER_FORMAT = 'VIRGIL %s';

    const CURL_FACTORY_OPTIONS = [CURLOPT_RETURNTRANSFER => 1, CURLOPT_HEADER => true];

    /** @var CardsServiceInterface */
    private $cardsService;

    /** @var RegistrationAuthorityServiceInterface */
    private $registrationAuthorityService;

    /** @var IdentityServiceInterface */
    private $identityService;

    /** @var CardValidatorInterface */
    private $cardValidator;

    /** @var SignedResponseCardMapper */
    private $cardMapper;


    /**
     * Class constructor.
     *
     * @param VirgilClientParamsInterface           $virgilClientParams
     * @param CardsServiceInterface                 $cardsService
     * @param RegistrationAuthorityServiceInterface $registrationAuthorityService
     * @param IdentityServiceInterface              $identityService
     */
    public function __construct(
        VirgilClientParamsInterface $virgilClientParams,
        CardsServiceInterface $cardsService = null,
        RegistrationAuthorityServiceInterface $registrationAuthorityService = null,
        IdentityServiceInterface $identityService = null
    ) {
        if ($cardsService === null) {
            $cardsService = $this->initializeCardService($virgilClientParams);
        }

        if ($registrationAuthorityService === null) {
            $registrationAuthorityService = $this->initializeRegistrationAuthorityService($virgilClientParams);
        }

        if ($identityService === null) {
            $identityService = $this->initializeIdentityService($virgilClientParams);
        }

        $this->cardsService = $cardsService;
        $this->registrationAuthorityService = $registrationAuthorityService;
        $this->identityService = $identityService;

        $this->cardMapper = new SignedResponseCardMapper();
    }


    /**
     * Makes client by provided access token.
     *
     * @param string $accessToken
     *
     * @return VirgilClient
     */
    public static function create($accessToken = null)
    {
        return new self(new VirgilClientParams($accessToken));
    }


    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function createCard(CreateCardRequest $createCardRequest)
    {
        $response = $this->cardsService->create($createCardRequest->getRequestModel());

        return $this->buildAndVerifyCard($response);
    }


    /**
     * @inheritdoc
     */
    public function publishGlobalCard(PublishGlobalCardRequest $publishGlobalCardRequest)
    {
        $response = $this->registrationAuthorityService->create($publishGlobalCardRequest->getRequestModel());

        return $this->buildAndVerifyCard($response);
    }


    /**
     * @inheritdoc
     */
    public function revokeCard(RevokeCardRequest $revokeCardRequest)
    {
        $this->cardsService->delete($revokeCardRequest->getRequestModel());

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function revokeGlobalCard(RevokeGlobalCardRequest $revokeGlobalCardRequest)
    {
        $this->registrationAuthorityService->delete($revokeGlobalCardRequest->getRequestModel());

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getCard($id)
    {
        $response = $this->cardsService->get($id);

        return $this->buildAndVerifyCard($response);
    }


    /**
     * @inheritdoc
     */
    public function verifyIdentity($identity, $identityType, array $extraFields = [])
    {
        $verifyRequest = new VerifyRequestModel($identityType, $identity, $extraFields);

        $verifyResponse = $this->identityService->verify($verifyRequest);

        return $verifyResponse->getActionId();
    }


    /**
     * @inheritdoc
     */
    public function confirmIdentity($actionId, $confirmationCode, $timeToLive = 3600, $countToLive = 1)
    {
        $confirmRequest = new ConfirmRequestModel(
            $confirmationCode, $actionId, new TokenModel($timeToLive, $countToLive)
        );

        $confirmResponse = $this->identityService->confirm($confirmRequest);

        return $confirmResponse->getValidationToken();
    }


    /**
     * @inheritdoc
     */
    public function isIdentityValid($identityType, $identity, $validationToken)
    {
        $validationRequest = new ValidateRequestModel($identityType, $identity, $validationToken);

        try {
            $this->identityService->validate($validationRequest);
        } catch (UnsuccessfulResponseException $exception) {
            if ($exception->getHttpStatusCode() != '400') {
                throw $exception;
            }

            return false;
        }

        return true;
    }


    /**
     * @inheritdoc
     */
    public function setCardValidator(CardValidatorInterface $validator)
    {
        $this->cardValidator = $validator;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setCardMapper(CardMapperInterface $cardMapper)
    {
        $this->cardMapper = $cardMapper;

        return $this;
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
     * @param SignedResponseModel $signedResponseModel
     *
     * @return Card
     *
     * @throws CardValidationException
     */
    private function buildAndVerifyCard(SignedResponseModel $signedResponseModel)
    {
        $card = $this->cardMapper->toCard($signedResponseModel);

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

        $curlRequestFactory = new CurlRequestFactory(self::CURL_FACTORY_OPTIONS);

        $httpHeaders = [
            'Authorization' => sprintf(self::AUTH_HEADER_FORMAT, $virgilClientParams->getAccessToken()),
        ];

        $curlClient = new CurlClient($curlRequestFactory, $httpHeaders);

        $jsonMappers = VirgilCardsMapperModelMappersCollection::getInstance();

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
    private function initializeRegistrationAuthorityService(VirgilClientParamsInterface $virgilClientParams)
    {
        $registrationAuthorityServiceHost = $virgilClientParams->getRegistrationAuthorityServiceAddress();

        $registrationAuthorityServiceParams = new RegistrationAuthorityServiceParams($registrationAuthorityServiceHost);

        $curlRequestFactory = new CurlRequestFactory(self::CURL_FACTORY_OPTIONS);

        $curlClient = new CurlClient($curlRequestFactory, ['Expect' => '']);

        $jsonMappers = RegistrationAuthorityModelMappersCollection::getInstance();

        $virgilServicesHttpClient = new HttpClient($curlClient, $jsonMappers->getErrorResponseModelMapper());

        return new RegistrationAuthorityService(
            $registrationAuthorityServiceParams, $virgilServicesHttpClient, $jsonMappers
        );
    }


    /**
     * Initialize default identity service.
     *
     * @param VirgilClientParamsInterface $virgilClientParams
     *
     * @return IdentityServiceInterface
     */
    private function initializeIdentityService(VirgilClientParamsInterface $virgilClientParams)
    {
        $identityServiceAddress = $virgilClientParams->getIdentityServiceAddress();

        $identityServiceParams = new IdentityServiceParams($identityServiceAddress);

        $curlRequestFactory = new CurlRequestFactory(self::CURL_FACTORY_OPTIONS);

        $curlClient = new CurlClient($curlRequestFactory);

        $jsonMappers = IdentityModelMappersCollection::getInstance();

        $virgilServicesHttpClient = new HttpClient($curlClient, $jsonMappers->getErrorResponseModelMapper());

        return new IdentityService($identityServiceParams, $virgilServicesHttpClient, $jsonMappers);
    }
}
