<?php

namespace Virgil\SDK\Client\Http;


class CurlClient implements ClientInterface
{
    private $curlRequestFactory;
    private $headers;

    /**
     * CurlClient constructor.
     * @param RequestFactoryInterface $curlRequestFactory
     * @param array $headers Default headers for all outbound requests.
     */
    public function __construct(RequestFactoryInterface $curlRequestFactory, array $headers = [])
    {
        $this->curlRequestFactory = $curlRequestFactory;
        $this->headers = $headers;
    }

    public function post($uri, $body, $headers = [])
    {
        $options = [
            CURLOPT_URL => $this->prepareUri($uri),
            CURLOPT_HTTPHEADER => $this->prepareHeaders($headers),
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $body
        ];

        $curlRequest = $this->curlRequestFactory->create($options);

        return $this->doRequest($curlRequest);
    }

    public function delete($uri, $body, $headers = [])
    {
        $curlRequest = $this->curlRequestFactory->create([
            CURLOPT_URL => $this->prepareUri($uri),
            CURLOPT_HTTPHEADER => $this->prepareHeaders($headers),
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $body
        ]);

        return $this->doRequest($curlRequest);
    }

    public function get($uri, $params = [], $headers = [])
    {
        $curlRequest = $this->curlRequestFactory->create([
            CURLOPT_URL => $this->prepareUri($uri, $params),
            CURLOPT_HTTPHEADER => $this->prepareHeaders($headers),
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPGET => true
        ]);

        return $this->doRequest($curlRequest);
    }

    public function doRequest(RequestInterface $request)
    {
        /** @var CurlRequest $request */
        $response = $request->execute();
        $status = $request->getInfo(CURLINFO_HTTP_CODE);
        $request->close();
        return $this->buildResponse($status, $response);
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    protected function buildResponse($status, $response)
    {
        return new Response(
            new Status($status),
            ...explode("\r\n\r\n", $response, 2)
        );
    }

    protected function prepareHeaders($headers)
    {
        $headers = $headers + $this->headers;
        $result = [];
        foreach ($headers as $headerKey => $header) {
            $result[] = ucfirst($headerKey) . ': ' . (is_array($header) ? implode(',', $header) : $header);
        }

        return $result;
    }

    protected function prepareUri($uri, $params = [])
    {
        if (!empty($params)) {
            $uri = $uri . '?' . http_build_query($params);
        }
        return $uri;
    }
}