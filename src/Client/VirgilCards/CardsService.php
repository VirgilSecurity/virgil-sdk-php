<?php
namespace Virgil\Sdk\Client\VirgilCards;


use Virgil\Sdk\Client\VirgilCards\Mapper\ModelMappersCollectionInterface;

use Virgil\Sdk\Client\VirgilCards\Model\ErrorResponseModel;
use Virgil\Sdk\Client\VirgilCards\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\SearchCriteria;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

use Virgil\Sdk\Client\Http\ClientInterface;
use Virgil\Sdk\Client\Http\ResponseInterface;

/**
 * Class responsible for retrieving, revocation or creation Virgil cards.
 */
class CardsService implements CardsServiceInterface
{
    const DEFAULT_ERROR_MESSAGES = [
        400 => 'Request error',
        401 => 'Authentication error',
        403 => 'Forbidden',
        404 => 'Entity not found',
        405 => 'Method not allowed',
        500 => 'Server error',
    ];

    /** @var ClientInterface $httpClient */
    private $httpClient;

    /** @var ModelMappersCollectionInterface $mappers */
    private $mappers;

    /** @var CardsServiceParamsInterface $params */
    private $params;


    /**
     * Class constructor.
     *
     * @param CardsServiceParamsInterface     $params
     * @param ClientInterface                 $httpClient
     * @param ModelMappersCollectionInterface $mappers
     */
    public function __construct(
        CardsServiceParamsInterface $params,
        ClientInterface $httpClient,
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
        $request = function () use ($model) {
            return $this->httpClient->post(
                $this->params->getCreateUrl(),
                $this->mappers->getSignedRequestModelMapper()->toJson($model)
            );
        };

        $response = $this->makeRequest($request);

        return $this->mappers->getSignedResponseModelMapper()->toModel($response->getBody());
    }


    /**
     * @inheritdoc
     */
    public function delete(SignedRequestModel $model)
    {
        $request = function () use ($model) {
            /** @var RevokeCardContentModel $cardContent */
            $cardContent = $model->getCardContent();

            return $this->httpClient->delete(
                $this->params->getDeleteUrl($cardContent->getId()),
                $this->mappers->getSignedRequestModelMapper()->toJson($model)
            );
        };

        $this->makeRequest($request);
    }


    /**
     * @inheritdoc
     */
    public function search(SearchCriteria $model)
    {
        $request = function () use ($model) {
            return $this->httpClient->post(
                $this->params->getSearchUrl(),
                $this->mappers->getSearchCriteriaRequestMapper()->toJson($model)
            );
        };

        $response = $this->makeRequest($request);

        return $this->mappers->getSearchCriteriaResponseMapper()->toModel($response->getBody());
    }


    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $request = function () use ($id) {
            return $this->httpClient->get($this->params->getGetUrl($id));
        };

        $response = $this->makeRequest($request);

        return $this->mappers->getSignedResponseModelMapper()->toModel($response->getBody());
    }


    /**
     * Makes request to http client and gets response object.
     *
     * @param callable $request
     *
     * @throws CardsServiceException
     * @return ResponseInterface
     */
    protected function makeRequest($request)
    {
        /** @var ResponseInterface $result */
        $result = call_user_func($request);

        if (!$result->getHttpStatus()->isSuccess()) {
            /** @var ErrorResponseModel $response */
            $response = $this->mappers->getErrorResponseModelMapper()->toModel($result->getBody());

            $httpStatus = (int)$result->getHttpStatus()->getStatus();
            $serviceErrorMessage = $response->getMessageOrDefault(self::DEFAULT_ERROR_MESSAGES[$httpStatus]);

            throw new CardsServiceException($serviceErrorMessage, $httpStatus);
        }

        return $result;
    }
}
