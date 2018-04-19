<?php

namespace Virgil\Http\Curl;


/**
 * Interface represents methods of cURL session.
 * @package Virgil\Http\Curl
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
     * @return $this
     */
    public function setOption($name, $option);


    /**
     * Set request options.
     *
     * @param array $options
     *
     * @return $this
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
     * @return $this
     */
    public function close();

}
