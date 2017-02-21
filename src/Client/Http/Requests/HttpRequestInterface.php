<?php
namespace Virgil\Sdk\Client\Http\Requests;


/**
 * Interface represents HTTP request.
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
