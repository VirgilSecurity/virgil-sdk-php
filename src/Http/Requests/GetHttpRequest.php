<?php

namespace Virgil\Sdk\Http\Requests;


use Virgil\Sdk\Http\Constants\RequestMethods;

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
