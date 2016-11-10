<?php

namespace Virgil\SDK\Client\Http;


interface RequestFactoryInterface
{
    /**
     * Creates RequestInterface by given options.
     * @param array $options
     * @return RequestInterface
     */
    public function create(array $options);

    /**
     * Setups default options for a factory.
     * @param array $options
     * @return mixed
     */
    public function setDefaultOptions(array $options);
}