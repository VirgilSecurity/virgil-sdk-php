<?php
namespace Virgil\Sdk\Client\Http\Curl;


/**
 * Interface represents methods of cURL session.
 */
interface RequestInterface
{
    /**
     * Execute curl request.
     *
     * @return mixed
     */
    public function execute();


    /**
     * Get info from request.
     *
     * @param string $option
     *
     * @return mixed
     */
    public function getInfo($option = null);


    /**
     * Set request option.
     *
     * @param string $name
     * @param mixed  $option
     *
     * @return void
     */
    public function setOption($name, $option);


    /**
     * Set request options.
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options);


    /**
     * Get all request options.
     *
     * @return array
     */
    public function getOptions();


    /**
     * Close a curl session.
     *
     * @return void
     */
    public function close();

}
