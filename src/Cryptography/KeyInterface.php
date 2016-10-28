<?php

namespace Virgil\SDK\Cryptography;


use Virgil\SDK\Buffer;

interface KeyInterface
{
    /**
     * Get receiver id for current key
     *
     * @return Buffer
     */
    public function getReceiverId();

    /**
     * Get key value
     *
     * @return Buffer
     */
    public function getValue();
}