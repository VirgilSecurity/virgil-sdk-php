<?php
namespace Virgil\Sdk\Client\Http\Curl;


use Virgil\Sdk\Client\Http\AbstractHttpClient;

use Virgil\Sdk\Client\Http\Constants\RequestMethods;

use Virgil\Sdk\Client\Http\Responses\HttpResponse;
use Virgil\Sdk\Client\Http\Responses\HttpStatusCode;

/**
 * Class represents curl client for making HTTP requests.
 */
class CurlClient extends AbstractHttpClient
{
    /** @var RequestFactoryInterface $curlRequestFactory */
    private $curlRequestFactory;

    /** @var array $requestHeaders */
    private $requestHeaders;


    /**
     * Class constructor.
     *
     * @param RequestFactoryInterface $curlRequestFactory Curl request factory
     * @param array                   $requestHeaders     Default headers for all outbound requests
     */
    public function __construct(RequestFactoryInterface $curlRequestFactory, array $requestHeaders = [])
    {
        $this->curlRequestFactory = $curlRequestFactory;
        $this->requestHeaders = $requestHeaders;
    }


    /**
     * @inheritdoc
     */
    public function post($requestUrl, $requestBody, array $requestHeaders = [])
    {
        $curlOptions = [
            CURLOPT_URL           => $this->buildUrl($requestUrl),
            CURLOPT_HTTPHEADER    => $this->buildHeaders($requestHeaders),
            CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_POST,
            CURLOPT_POSTFIELDS    => $requestBody,
            CURLOPT_POST          => true,
        ];

        $curlRequest = $this->curlRequestFactory->create($curlOptions);

        return $this->doRequest($curlRequest);
    }


    /**
     * @inheritdoc
     */
    public function delete($requestUrl, $requestBody, array $requestHeaders = [])
    {
        $curlRequest = $this->curlRequestFactory->create(
            [
                CURLOPT_URL           => $this->buildUrl($requestUrl),
                CURLOPT_HTTPHEADER    => $this->buildHeaders($requestHeaders),
                CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_DELETE,
                CURLOPT_POSTFIELDS    => $requestBody,
                CURLOPT_POST          => true,
            ]
        );

        return $this->doRequest($curlRequest);
    }


    /**
     * @inheritdoc
     */
    public function get($requestUrl, array $requestParams = [], array $requestHeaders = [])
    {

        $curlRequest = $this->curlRequestFactory->create(
            [
                CURLOPT_URL           => $this->buildUrl($requestUrl, $requestParams),
                CURLOPT_HTTPHEADER    => $this->buildHeaders($requestHeaders),
                CURLOPT_CUSTOMREQUEST => RequestMethods::HTTP_GET,
                CURLOPT_HTTPGET       => true,
            ]
        );

        return $this->doRequest($curlRequest);
    }


    /**
     * @inheritdoc
     */
    public function getRequestHeaders()
    {
        return $this->requestHeaders;
    }


    /**
     * @inheritdoc
     */
    public function setRequestHeaders($requestHeaders)
    {
        $this->requestHeaders = $requestHeaders;
    }


    /**
     * @inheritdoc
     *
     * @return HttpResponse
     */
    protected function doRequest(RequestInterface $httpRequest)
    {
        $httpRawResponse = $httpRequest->execute();
        $httpStatusCode = $httpRequest->getInfo(CURLINFO_HTTP_CODE);
        $httpRequest->close();

        return $this->buildResponse($httpStatusCode, $httpRawResponse);
    }


    /**
     * Builds response from raw HTTP response body and HTTP status code.
     *
     * @param string $httpStatusCode HTTP status code
     * @param string $httpResponse   Raw HTTP response body
     *
     * @return HttpResponse
     */
    protected function buildResponse($httpStatusCode, $httpResponse)
    {
        return new HttpResponse(new HttpStatusCode($httpStatusCode), ...explode("\r\n\r\n", $httpResponse, 2));
    }


    /**
     * Returns HTTP compatible request headers.
     *
     * @param $requestHeaders
     *
     * @return array
     */
    protected function buildHeaders($requestHeaders)
    {
        $requestHeaders = $requestHeaders + $this->requestHeaders;
        $resultHeaders = [];

        foreach ($requestHeaders as $headerName => $headerValue) {

            if (is_array($headerValue)) {
                $headerValue = implode(',', $headerValue);
            }

            $resultHeaders[] = ucfirst($headerName) . ': ' . $headerValue;
        }

        return $resultHeaders;
    }


    /**
     * Returns HTTP compatible request URL with params if specified.
     *
     * @param       $requestUrl
     * @param array $requestParams
     *
     * @return string
     */
    protected function buildUrl($requestUrl, $requestParams = [])
    {
        if (!empty($requestParams)) {
            $requestUrl = $requestUrl . '?' . http_build_query($requestParams);
        }

        return $requestUrl;
    }
}
