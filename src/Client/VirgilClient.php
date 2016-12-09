<?php
namespace Virgil\Sdk\Client;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Card\CardsServiceParams;
use Virgil\Sdk\Client\Card\CardsService;
use Virgil\Sdk\Client\Card\CardsServiceInterface;
use Virgil\Sdk\Client\Card\Mapper;

use Virgil\Sdk\Client\Requests;

use Virgil\Sdk\Client\Card\Model\SearchCriteria;
use Virgil\Sdk\Client\Card\Model\SignedResponseModel;

use Virgil\Sdk\Client\Http\CurlClient;
use Virgil\Sdk\Client\Http\CurlRequestFactory;

use Virgil\Sdk\Client\Validator\CardValidationException;
use Virgil\Sdk\Client\Validator\CardValidatorInterface;

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
     * @param SearchCriteria $criteria
     *
     * @return Card[]
     */
    public function searchCards(SearchCriteria $criteria)
    {
        $response = $this->cardsService->search($criteria);

        $responseModelToCard = function (SignedResponseModel $responseModel) {
            return $this->buildAndVerifyCard($responseModel);
        };

        return array_map($responseModelToCard, $response);
    }


    /**
     * Performs the Virgil Cards service card creation by request.
     *
     * @param Requests\CreateCardRequest $request
     *
     * @return Card
     */
    public function createCard(Requests\CreateCardRequest $request)
    {
        $response = $this->cardsService->create($request->getRequestModel());

        return $this->buildAndVerifyCard($response);
    }


    /**
     * Performs the Virgil Cards service card revoking by request.
     *
     * @param Requests\RevokeCardRequest $request
     *
     * @return void
     */
    public function revokeCard(Requests\RevokeCardRequest $request)
    {
        $this->cardsService->delete($request->getRequestModel());
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
     * @return void
     */
    public function setCardValidator(CardValidatorInterface $validator)
    {
        $this->cardValidator = $validator;
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

        //TODO: decide to send additional headers like Content-Type (need negotiate).
        $httpHeaders = [
            'Authorization' => sprintf(self::AUTH_HEADER_FORMAT, $virgilClientParams->getAccessToken()),
        ];

        $curlClient = new CurlClient($curlRequestFactory, $httpHeaders);

        $signedResponseModelMapper = new Mapper\SignedResponseModelMapper();

        $jsonMappers = new Mapper\ModelMappersCollection(
            $signedResponseModelMapper,
            new Mapper\SignedRequestModelMapper(),
            new Mapper\SearchCriteriaResponseMapper($signedResponseModelMapper),
            new Mapper\SearchCriteriaRequestMapper(),
            new Mapper\ErrorResponseModelMapper()
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
        $responseModelSigns = $responseModel->getMeta()->getSigns();

        $responseModelSignsToCardSigns = function ($sign) {
            return Buffer::fromBase64($sign);
        };

        $cardSigns = array_map($responseModelSignsToCardSigns, $responseModelSigns);

        return new Card(
            $responseModel->getId(),
            Buffer::fromBase64($responseModel->getSnapshot()),
            $responseModel->getCardContent()->getIdentity(),
            $responseModel->getCardContent()->getIdentityType(),
            Buffer::fromBase64($responseModel->getCardContent()->getPublicKey()),
            $responseModel->getCardContent()->getScope(),
            $responseModel->getCardContent()->getData(),
            $responseModel->getCardContent()->getInfo()->getDevice(),
            $responseModel->getCardContent()->getInfo()->getDeviceName(),
            $responseModel->getMeta()->getCardVersion(),
            $cardSigns
        );
    }


    /**
     * Validate card.
     *
     * @param Card $card
     *
     * @return void
     *
     * @throws CardValidationException
     */
    private function validateCard(Card $card)
    {
        if ($this->cardValidator == null) {
            return;
        }

        $this->cardValidator->validate($card);
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
