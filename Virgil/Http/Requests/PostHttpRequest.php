<?php

namespace Virgil\Http\Requests;


use Virgil\Http\Constants\RequestMethods;

/**
 * Class PostHttpRequest
 * @package Virgil\Http\Requests
 */
class PostHttpRequest extends AbstractHttpRequest
{
    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::HTTP_POST;
    }
}
