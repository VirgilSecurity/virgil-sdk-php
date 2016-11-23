<?php

namespace Virgil\SDK\Cryptography;


use Virgil\SDK\Buffer;

class VirgilKeyEntry implements KeyEntryInterface
{
    protected $receiverId;

    protected $value;

    /**
     * VirgilKeyEntry constructor.
     * @param string $receiverId receiver id for current key
     * @param string $value DER key value
     */
    public function __construct($receiverId, $value)
    {
        $this->receiverId = new Buffer($receiverId);
        $this->value = new Buffer($value);
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