<?php

namespace Virgil\Sdk\Http\Requests;

use Virgil\Sdk\Http\Constants\RequestMethods;


/**
 * Class PostHttpRequest
 */
class PostHttpRequest extends AbstractHttpRequest
{
    public function __construct(string $url, string $body, array $headers = [])
    {
        parent::__construct($url, $body, $headers);
    }

    public function getMethod()
    {
        return RequestMethods::HTTP_POST;
    }
}
