<?php

namespace Virgil\Http\Responses;


/**
 * Interface represents HTTP status code.
 * @package Virgil\Http\Responses
 */
interface HttpStatusCodeInterface
{
    /**
     * Check if status is successful.
     *
     * @return bool
     */
    public function isSuccess();


    /**
     * Get status code value.
     *
     * @return string
     */
    public function getCode();
}
