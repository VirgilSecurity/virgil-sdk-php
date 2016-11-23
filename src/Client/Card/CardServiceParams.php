<?php

namespace Virgil\SDK\Client\Card;


use InvalidArgumentException;

class CardServiceParams implements CardServiceParamsInterface
{
    private $immutableHostKey = 'immutable_host';
    private $mutableHostKey = 'mutable_host';
    private $searchEndpointKey = 'search_endpoint';
    private $createEndpointKey = 'create_endpoint';
    private $deleteEndpointKey = 'delete_endpoint';
    private $getEndpointKey = 'get_endpoint';
    private $searchEndpoint;
    private $createEndpoint;
    private $deleteEndpoint;
    private $getEndpoint;

    /**
     * CardServiceParams constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->validateParams($params);

        $this->searchEndpoint = $this->buildEndpoint($params[$this->immutableHostKey], $params[$this->searchEndpointKey]);
        $this->createEndpoint = $this->buildEndpoint($params[$this->mutableHostKey], $params[$this->createEndpointKey]);
        $this->deleteEndpoint = $this->buildEndpoint($params[$this->mutableHostKey], $params[$this->deleteEndpointKey]);
        $this->getEndpoint = $this->buildEndpoint($params[$this->immutableHostKey], $params[$this->getEndpointKey]);
    }

    /**
     * Build endpoint from given host and uri.
     *
     * @param string $host
     * @param string $uri
     * @return string
     */
    protected function buildEndpoint($host, $uri)
    {
        return rtrim($host, '/') . '/' . trim($uri, '/');
    }

    /**
     * Validate params.
     *
     * @param array $params
     */
    protected function validateParams(array $params)
    {
        foreach (
            [$this->immutableHostKey, $this->mutableHostKey, $this->getEndpointKey, $this->searchEndpointKey, $this->deleteEndpointKey, $this->createEndpointKey]
            as $key
        ) {
            if (!array_key_exists($key, $params)) {
                throw new InvalidArgumentException($key . ' key is required');
            }
        }
    }

    public function getSearchEndpoint()
    {
        return $this->searchEndpoint;
    }

    public function getCreateEndpoint()
    {
        return $this->createEndpoint;
    }

    public function getDeleteEndpoint($id = null)
    {
        return $id === null ? $this->deleteEndpoint : $this->deleteEndpoint . '/' . $id;
    }

    public function getGetEndpoint($id = null)
    {
        return $id === null ? $this->getEndpoint : $this->getEndpoint . '/' . $id;
    }
}