<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards;


use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\Http\Requests\DeleteHttpRequest;
use Virgil\Sdk\Client\Http\Requests\GetHttpRequest;
use Virgil\Sdk\Client\Http\Requests\HttpRequestInterface;
use Virgil\Sdk\Client\Http\Requests\PostHttpRequest;

use Virgil\Sdk\Client\Http\Responses\HttpResponseInterface;

use Virgil\Sdk\Client\VirgilServices\UnsuccessfulResponseException;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\ModelMappersCollectionInterface;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SearchRequestModel;
use Virgil\Sdk\Client\VirgilServices\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestModel;

/**
 * Class responsible for retrieving, revocation or creation Virgil cards.
 */
class CardsService implements CardsServiceInterface
{
    /** @var HttpClientInterface $httpClient */
    protected $httpClient;

    /** @var ModelMappersCollectionInterface $mappers */
    protected $mappers;

    /** @var CardsServiceParamsInterface $params */
    protected $params;


    /**
     * Class constructor.
     *
     * @param CardsServiceParamsInterface     $params
     * @param HttpClientInterface             $httpClient
     * @param ModelMappersCollectionInterface $mappers
     */
    public function __construct(
        CardsServiceParamsInterface $params,
        HttpClientInterface $httpClient,
        ModelMappersCollectionInterface $mappers
    ) {
        $this->httpClient = $httpClient;
        $this->mappers = $mappers;
        $this->params = $params;
    }


    /**
     * @inheritdoc
     */
    public function create(SignedRequestModel $model)
    {
        $signedResponseModelMapper = $this->mappers->getSignedResponseModelMapper();
        $signedRequestModelMapper = $this->mappers->getSignedRequestModelMapper();

        $createCardHttpRequest = new PostHttpRequest(
            $this->params->getCreateUrl(), $signedRequestModelMapper->toJson($model)
        );

        $httpResponse = $this->makeRequest($createCardHttpRequest);

        return $signedResponseModelMapper->toModel($httpResponse->getBody());
    }


    /**
     * @inheritdoc
     */
    public function delete(SignedRequestModel $model)
    {
        $signedRequestModelMapper = $this->mappers->getSignedRequestModelMapper();

        /** @var RevokeCardContentModel $cardContent */
        $cardContent = $model->getRequestContent();

        $deleteCardHttpRequest = new DeleteHttpRequest(
            $this->params->getDeleteUrl($cardContent->getId()), $signedRequestModelMapper->toJson($model)
        );

        $this->makeRequest($deleteCardHttpRequest);

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function search(SearchRequestModel $model)
    {
        $signedResponseModelsMapper = $this->mappers->getSignedResponseModelsMapper();
        $searchRequestModelMapper = $this->mappers->getSearchRequestModelMapper();

        $searchCardsHttpRequest = new PostHttpRequest(
            $this->params->getSearchUrl(), $searchRequestModelMapper->toJson($model)
        );

        $httpResponse = $this->makeRequest($searchCardsHttpRequest);

        return $signedResponseModelsMapper->toModel($httpResponse->getBody());
    }


    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $signedResponseModelMapper = $this->mappers->getSignedResponseModelMapper();
        $getCardHttpRequest = new GetHttpRequest($this->params->getGetUrl($id));

        $httpResponse = $this->makeRequest($getCardHttpRequest);

        return $signedResponseModelMapper->toModel($httpResponse->getBody());
    }


    /**
     * @param HttpRequestInterface $request
     *
     * @return HttpResponseInterface
     *
     * @throws CardsServiceException
     */
    protected function makeRequest(HttpRequestInterface $request)
    {
        try {
            return $this->httpClient->send($request);
        } catch (UnsuccessfulResponseException $exception) {
            throw new CardsServiceException(
                $exception->getMessage(), $exception->getHttpStatusCode(), $exception->getServiceErrorCode()
            );
        }
    }
}
