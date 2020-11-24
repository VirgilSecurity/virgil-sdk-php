<?php

namespace Virgil\Sdk\Http\Requests;


/**
 * Interface HttpRequestInterface
 * @package Virgil\Http\Requests
 */
interface HttpRequestInterface
{
    /**
     * @return string
     */
    public function getUrl();


    /**
     * @return mixed
     */
    public function getBody();


    /**
     * @return string
     */
    public function getMethod();


    /**
     * @return array
     */
    public function getHeaders();
}
