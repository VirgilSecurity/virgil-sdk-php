<?php

namespace Virgil\Http\Requests;


use Virgil\Http\Constants\RequestMethods;

/**
 * Class GetHttpRequest
 * @package Virgil\Http\Requests
 */
class GetHttpRequest extends AbstractHttpRequest
{
    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::HTTP_GET;
    }
}
