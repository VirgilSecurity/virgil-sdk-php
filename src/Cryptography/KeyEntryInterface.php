<?php

namespace Virgil\SDK\Cryptography;


use Virgil\SDK\BufferInterface;

interface KeyEntryInterface
{
    /**
     * @return BufferInterface
     */
    public function getReceiverId();

    /**
     * @return BufferInterface
     */
    public function getValue();
}