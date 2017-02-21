<?php
namespace Virgil\Sdk\Client\VirgilServices\Http;


use Virgil\Sdk\Client\Http\HttpClientInterface;

use Virgil\Sdk\Client\Http\Requests\HttpRequestInterface;

use Virgil\Sdk\Client\VirgilServices\Mapper\JsonModelMapperInterface;

use Virgil\Sdk\Client\VirgilServices\UnsuccessfulResponseException;

/**
 * Class is middleware for convenient sending http requests to Virgil services.
 */
class HttpClient implements HttpClientInterface
{
    const HTTP_RESPONSE_CODE_MESSAGES = [
        200 => 'Success',
        400 => 'Request error',
        401 => 'Authentication error',
        403 => 'Forbidden',
        404 => 'Entity not found',
        405 => 'Method not allowed',
        500 => 'Server error',
    ];

    /** @var JsonModelMapperInterface */
    private $errorResponseModelMapper;

    /** @var HttpClientInterface */
    private $httpClient;


    /**
     * Class constructor.
     *
     * @param HttpClientInterface      $httpClient
     * @param JsonModelMapperInterface $errorResponseModelMapper
     */
    public function __construct(HttpClientInterface $httpClient, JsonModelMapperInterface $errorResponseModelMapper)
    {
        $this->errorResponseModelMapper = $errorResponseModelMapper;
        $this->httpClient = $httpClient;
    }


    /**
     * @inheritdoc
     */
    public function post($requestUrl, $requestBody, array $requestHeaders = [])
    {
        $this->httpClient->post($requestUrl, $requestBody, $requestHeaders);
    }


    /**
     * @inheritdoc
     */
    public function delete($requestUrl, $requestBody, array $requestHeaders = [])
    {
        $this->httpClient->delete($requestUrl, $requestBody, $requestHeaders);
    }


    /**
     * @inheritdoc
     */
    public function get($requestUrl, array $requestParams = [], array $requestHeaders = [])
    {
        $this->httpClient->get($requestUrl, $requestParams, $requestHeaders);
    }


    /**
     * Sends http request to web service and returns response.
     * Throws exception in case unsuccessful HTTP response.
     *
     * @inheritdoc
     *
     * @throws UnsuccessfulResponseException
     */
    public function send(HttpRequestInterface $httpRequest)
    {
        $response = $this->httpClient->send($httpRequest);

        $responseHttpStatusCode = $response->getHttpStatusCode();

        if (!$responseHttpStatusCode->isSuccess()) {
            $errorResponseModelMapper = $this->errorResponseModelMapper;

            $errorResponseModel = $errorResponseModelMapper->toModel($response->getBody());

            $httpStatusCode = $responseHttpStatusCode->getCode();

            $serviceErrorMessage = $errorResponseModel->getMessage();

            $serviceErrorCode = $errorResponseModel->getCode();

            if ($serviceErrorMessage == '') {
                $serviceErrorMessage = self::HTTP_RESPONSE_CODE_MESSAGES[(int)$httpStatusCode];
            }

            throw new UnsuccessfulResponseException($serviceErrorMessage, $httpStatusCode, $serviceErrorCode);
        }

        return $response;
    }


    /**
     * @inheritdoc
     */
    public function getRequestHeaders()
    {
        $this->httpClient->getRequestHeaders();
    }


    /**
     * @inheritdoc
     */
    public function setRequestHeaders($requestHeaders)
    {
        $this->httpClient->setRequestHeaders($requestHeaders);
    }
}
