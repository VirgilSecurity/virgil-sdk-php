<?php

namespace Virgil\Sdk\Http\Requests;


use Virgil\Http\Constants\RequestMethods;

/**
 * Class DeleteHttpRequest
 * @package Virgil\Http\Requests
 */
class DeleteHttpRequest extends AbstractHttpRequest
{
    /**
     * @inheritdoc
     */
    public function getMethod()
    {
        return RequestMethods::HTTP_DELETE;
    }
}
