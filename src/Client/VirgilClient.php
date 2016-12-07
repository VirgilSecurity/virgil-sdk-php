<?php
namespace Virgil\Sdk\Client;


use Virgil\Sdk\Buffer;
use Virgil\Sdk\Client\Card\CardServiceParams;
use Virgil\Sdk\Client\Card\CardsService;
use Virgil\Sdk\Client\Card\CardsServiceInterface;
use Virgil\Sdk\Client\Card\Mapper\ErrorResponseModelMapper;
use Virgil\Sdk\Client\Card\Mapper\ModelMappersCollection;
use Virgil\Sdk\Client\Card\Mapper\SearchCriteriaRequestMapper;
use Virgil\Sdk\Client\Card\Mapper\SearchCriteriaResponseMapper;
use Virgil\Sdk\Client\Card\Mapper\SignedRequestModelMapper;
use Virgil\Sdk\Client\Card\Mapper\SignedResponseModelMapper;
use Virgil\Sdk\Client\Card\Model\SearchCriteria;
use Virgil\Sdk\Client\Card\Model\SignedResponseModel;
use Virgil\Sdk\Client\Http\CurlClient;
use Virgil\Sdk\Client\Http\CurlRequestFactory;

class VirgilClient
{
    private $cardsService;
    /** @var  CardValidatorInterface */
    private $cardValidator;


    /**
     * VirgilClient constructor.
     *
     * @param VirgilClientParams    $virgilClientParams
     * @param CardsServiceInterface $cardsService
     */
    public function __construct(VirgilClientParams $virgilClientParams, CardsServiceInterface $cardsService = null)
    {
        $cardsService === null ? $this->cardsService = $this->initializeCardService(
            $virgilClientParams
        ) : $this->cardsService = $cardsService;
    }


    /**
     * Makes client by provided access token
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
     * @param SearchCriteria $criteria
     *
     * @return Card[]
     */
    public function searchCards(SearchCriteria $criteria)
    {
        $response = $this->cardsService->search($criteria);

        return array_map(
            function (SignedResponseModel $responseModel) {
                return $this->buildAndVerifyCard($responseModel);
            },
            $response
        );
    }


    /**
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
     * @param RevokeCardRequest $request
     */
    public function revokeCard(RevokeCardRequest $request)
    {
        $this->cardsService->delete($request->getRequestModel());
    }


    /**
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
     */
    public function setCardValidator(CardValidatorInterface $validator)
    {
        $this->cardValidator = $validator;
    }


    /**
     * @param VirgilClientParams $virgilClientParams
     *
     * @return CardsService
     */
    private function initializeCardService(VirgilClientParams $virgilClientParams)
    {
        $params = new CardServiceParams(
            [
                'mutable_host'    => $virgilClientParams->getCardsServiceAddress(),
                'immutable_host'  => $virgilClientParams->getReadOnlyCardsServiceAddress(),
                'search_endpoint' => '/v4/card/actions/search',
                'create_endpoint' => '/v4/card',
                'delete_endpoint' => '/v4/card',
                'get_endpoint'    => '/v4/card',
            ]
        );

        $httpClient = new CurlClient(
            new CurlRequestFactory([CURLOPT_RETURNTRANSFER => 1, CURLOPT_HEADER => true]), [
            'Authorization' => 'VIRGIL ' . $virgilClientParams->getAccessToken(),
        ]
        );

        $mappers = new ModelMappersCollection(
            new SignedResponseModelMapper(),
            new SignedRequestModelMapper(),
            new SearchCriteriaResponseMapper(new SignedResponseModelMapper()),
            new SearchCriteriaRequestMapper(),
            new ErrorResponseModelMapper()
        );

        return new CardsService($params, $httpClient, $mappers);
    }


    /**
     * @param SignedResponseModel $responseModel
     *
     * @return Card
     */
    private function responseToCard(SignedResponseModel $responseModel)
    {
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
            call_user_func(
                function ($signs) {
                    foreach ($signs as &$sign) {
                        $sign = Buffer::fromBase64($sign);
                    }

                    return $signs;
                },
                $responseModel->getMeta()->getSigns()
            )
        );
    }


    /**
     * Validate card.
     *
     * @param Card $card
     *
     * @throws CardValidationException
     */
    private function validateCard(Card $card)
    {
        if ($this->cardValidator == null) {
            return;
        }

        $isValid = $this->cardValidator->validate($card);
        if (!$isValid) {
            throw new CardValidationException('Card with id' . $card->getId() . ' is invalid. Please check signs.');
        }
    }


    /**
     * Builds and verify card from response.
     *
     * @param SignedResponseModel $responseModel
     *
     * @throws CardValidationException
     * @return Card
     */
    private function buildAndVerifyCard(SignedResponseModel $responseModel)
    {
        $card = $this->responseToCard($responseModel);
        $this->validateCard($card);

        return $card;
    }
}
