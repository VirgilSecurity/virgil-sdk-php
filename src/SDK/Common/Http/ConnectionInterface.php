<?php

namespace Virgil\SDK\Common\Http;

interface ConnectionInterface {

    /**
     * @return string
     */
    public function getBaseUrl();

    /**
     * @param RequestInterface $request
     * @return Response
     */
    public function send(RequestInterface $request);

    /**
     * @return string
     */
    public function getApiVersion();

    /**
     * @param $headers
     * @return mixed
     */
    public function setHeaders($headers);

}