<?php
namespace Virgil\Sdk\Client\Http\Responses;


/**
 * Interface represents HTTP status code.
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
