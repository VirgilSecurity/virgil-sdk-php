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
     * TODO is it always need to use this method with provided $options array. May be is it possible to to use default $options = [] parameter
     */
    public function create(array $options)
    {
        $request = new CurlRequest();
        $request->setOptions($options + $this->defaultOptions);

        return $request;
    }


    /**
     * @inheritdoc
     * TODO return $this in case if nothing useful to return
     */
    public function setDefaultOptions(array $options)
    {
        $this->defaultOptions = $options;
    }
}
