<?php
namespace Virgil\Sdk\Client\Http;


interface StatusInterface
{
    /**
     * Check if status is successful.
     *
     * @return bool
     */
    public function isSuccess();


    /**
     * Get status value.
     *
     * @return string
     */
    public function getStatus();
}
