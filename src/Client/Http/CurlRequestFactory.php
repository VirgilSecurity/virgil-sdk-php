<?php

namespace Virgil\SDK\Client\Http;


class CurlRequestFactory implements RequestFactoryInterface
{
    protected $defaultOptions = [];

    public function create(array $options)
    {
        $request = new CurlRequest();
        $request->setOptions($options + $this->defaultOptions);
        return $request;
    }

    public function setDefaultOptions(array $options)
    {
        $this->defaultOptions = $options;
    }
}