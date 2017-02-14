<?php
namespace Virgil\Sdk\Client\Http\Requests;


/**
 * Class HttpRequest
 * @package Virgil\Sdk\Client\Http\Request
 */
abstract class AbstractHttpRequest implements HttpRequestInterface
{
    /** @var string */
    private $url;

    /** @var mixed|null */
    private $body;

    /** @var array */
    private $headers;


    /**
     * Class constructor.
     *
     * @param string $url
     * @param mixed  $body
     * @param array  $headers
     */
    public function __construct($url, $body = null, $headers = [])
    {
        $this->url = $url;
        $this->body = $body;
        $this->headers = $headers;
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }


    /**
     * Returns request method.
     *
     * @return string
     */
    abstract public function getMethod();


    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
