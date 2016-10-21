<?php

namespace Virgil\SDK\Cryptography;


class VirgilKey implements Key
{
    /** @var string  */
    protected $receiverId;

    /** @var string  */
    protected $value;

    /**
     * VirgilKey constructor.
     * @param string $receiverId public key hash
     * @param string $value DER key value
     */
    public function __construct($receiverId, $value)
    {
        $this->receiverId = $receiverId;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getReceiverId()
    {
        return $this->receiverId;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}