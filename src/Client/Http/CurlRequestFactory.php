<?php
namespace Virgil\Sdk\Client\Http;


class CurlRequestFactory implements RequestFactoryInterface
{
    protected $defaultOptions = [];

    /**
     * CurlRequestFactory constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->defaultOptions = $options;
    }

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
