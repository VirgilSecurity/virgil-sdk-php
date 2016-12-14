<?php
namespace Virgil\Sdk\Client\Http\Curl;


/**
 * Class provides factory for creation curl requests.
 */
class CurlRequestFactory implements RequestFactoryInterface
{
    /** @var array $defaultOptions */
    protected $defaultOptions = [];


    /**
     * Class constructor.
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
