<?php

namespace Virgil\SDK\Cryptography;


interface KeyInterface
{
    /**
     * Get receiver id for current key
     *
     * @return string
     */
    public function getReceiverId();

    /**
     * Get DER key value
     *
     * @return string
     */
    public function getValue();
}