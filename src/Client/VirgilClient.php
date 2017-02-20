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
 */
class VirgilClient
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
     * Sends the request for identity verification, that's will be processed depending of specified type.
     *
     * @param string $identity
     * @param string $identityType
     * @param array  $extraFields
     *
     * @return string Returns action id.
     */
    public function verifyIdentity($identity, $identityType, array $extraFields = null)
    {
        $verifyRequest = new VerifyRequestModel($identityType, $identity, $extraFields);

        $verifyResponse = $this->identityService->verify($verifyRequest);

        return $verifyResponse->getActionId();
    }


    /**
     * Confirms the identity using confirmation code, that has been generated to confirm an identity.
     *
     * @param string $actionId
     * @param string $confirmationCode
     * @param int    $timeToLive
     * @param int    $countToLive
     *
     * @return string Returns validation token.
     */
    public function confirmIdentity($actionId, $confirmationCode, $timeToLive = 3600, $countToLive = 1)
    {
        $confirmRequest = new ConfirmRequestModel(
            $actionId, $confirmationCode, new TokenModel($timeToLive, $countToLive)
        );

        $confirmResponse = $this->identityService->confirm($confirmRequest);

        return $confirmResponse->getValidationToken();
    }


    /**
     * Checks if validation token is valid
     *
     * @param string $identityType
     * @param string $identity
     * @param string $validationToken
     *
     * @throws UnsuccessfulResponseException
     *
     * @return bool
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

        $curlClient = new CurlClient($curlRequestFactory);

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
