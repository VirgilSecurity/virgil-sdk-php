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


    /**
     * @inheritdoc
     */
    public function create(array $options)
    {
        $request = new CurlRequest();
        $request->setOptions($options + $this->defaultOptions);

        return $request;
    }


    /**
     * @inheritdoc
     */
    public function setDefaultOptions(array $options)
    {
        $this->defaultOptions = $options;
    }
}
