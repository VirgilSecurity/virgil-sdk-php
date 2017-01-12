<?php
namespace Virgil\Sdk\Client;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Requests\SearchCardRequest;
use Virgil\Sdk\Client\Requests\CreateCardRequest;
use Virgil\Sdk\Client\Requests\RevokeCardRequest;

use Virgil\Sdk\Client\VirgilCards\CardsServiceParams;
use Virgil\Sdk\Client\VirgilCards\CardsService;
use Virgil\Sdk\Client\VirgilCards\CardsServiceInterface;

use Virgil\Sdk\Client\VirgilCards\Mapper\CardContentModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\ErrorResponseModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\ModelMappersCollection;
use Virgil\Sdk\Client\VirgilCards\Mapper\SearchCriteriaRequestMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SearchCriteriaResponseMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SignedRequestModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SignedResponseModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;

use Virgil\Sdk\Client\Validator\CardValidationException;
use Virgil\Sdk\Client\Validator\CardValidatorInterface;

use Virgil\Sdk\Client\Http\Curl\CurlClient;
use Virgil\Sdk\Client\Http\Curl\CurlRequestFactory;

/**
 * Before you can use any Virgil services features in your app, you must first initialize VirgilClient class.
 * You use the VirgilClient object to get access to Create, Revoke and Search for Virgil Cards (Public keys).
 */
class VirgilClient
{
    const AUTH_HEADER_FORMAT = 'VIRGIL %s';

    /** @var CardsServiceInterface $cardsService */
    private $cardsService;

    /** @var CardValidatorInterface */
    private $cardValidator;


    /**
     * Class constructor.
     *
     * @param VirgilClientParamsInterface $virgilClientParams
     * @param CardsServiceInterface       $cardsService
     */
    public function __construct(
        VirgilClientParamsInterface $virgilClientParams,
        CardsServiceInterface $cardsService = null
    ) {
        if ($cardsService === null) {
            $cardsService = $this->initializeCardService($virgilClientParams);
        }

        $this->cardsService = $cardsService;
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
     * Performs the Virgil Cards service searching by criteria.
     *
     * @param SearchCardRequest $searchCardRequest
     *
     * @return Card[]
     */
    public function searchCards(SearchCardRequest $searchCardRequest)
    {
        $response = $this->cardsService->search($searchCardRequest->getSearchCriteria());

        $responseModelToCard = function (SignedResponseModel $responseModel) {
            return $this->buildAndVerifyCard($responseModel);
        };

        return array_map($responseModelToCard, $response);
    }


    /**
     * Performs the Virgil Cards service card creation by request.
     *
     * @param CreateCardRequest $request
     *
     * @return Card
     */
    public function createCard(CreateCardRequest $request)
    {
        $response = $this->cardsService->create($request->getRequestModel());

        return $this->buildAndVerifyCard($response);
    }


    /**
     * Performs the Virgil Cards service card revoking by request.
     *
     * @param RevokeCardRequest $request
     *
     * @return $this
     */
    public function revokeCard(RevokeCardRequest $request)
    {
        $this->cardsService->delete($request->getRequestModel());

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
     * Initialize default card service.
     *
     * @param VirgilClientParamsInterface $virgilClientParams
     *
     * @return CardsService
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

        $jsonMappers = new ModelMappersCollection(
            $signedResponseModelMapper,
            new SignedRequestModelMapper(),
            new SearchCriteriaResponseMapper($signedResponseModelMapper),
            new SearchCriteriaRequestMapper(),
            new ErrorResponseModelMapper()
        );

        return new CardsService($cardsServiceParams, $curlClient, $jsonMappers);
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
}
