<?php

namespace Virgil\SDK\Cryptography;


class VirgilKey implements KeyInterface
{
    protected $receiverId;

    protected $value;

    /**
     * VirgilKey constructor.
     * @param string $receiverId receiver id for current key
     * @param string $value DER key value
     */
    public function __construct($receiverId, $value)
    {
        $this->receiverId = $receiverId;
        $this->value = $value;
    }

    public function getReceiverId()
    {
        return $this->receiverId;
    }

    public function getValue()
    {
        return $this->value;
    }
}