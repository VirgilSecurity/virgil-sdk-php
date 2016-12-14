<?php
namespace Virgil\Sdk\Client\Http\Curl;


/**
 * Interface provides methods for curl request creation and configuration.
 */
interface RequestFactoryInterface
{
    /**
     * Creates curl request by given options.
     *
     * @param array $options
     *
     * @return RequestInterface
     */
    public function create(array $options);


    /**
     * Setups default options for a factory.
     *
     * @param array $options
     *
     * @return void
     */
    public function setDefaultOptions(array $options);
}
