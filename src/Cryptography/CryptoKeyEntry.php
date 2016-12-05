<?php
namespace Virgil\Sdk\Cryptography;


use Virgil\Sdk\Buffer;

class CryptoKeyEntry
{
    protected $receiverId;
    protected $value;

    /**
     * VirgilKeyEntry constructor.
     *
     * @param string $receiverId receiver id for current key
     * @param string $value DER key value
     */
    public function __construct($receiverId, $value)
    {
        $this->receiverId = new Buffer($receiverId);
        $this->value = new Buffer($value);
    }

    /**
     * @return Buffer
     */
    public function getReceiverId()
    {
        return $this->receiverId;
    }

    /**
     * @return Buffer
     */
    public function getValue()
    {
        return $this->value;
    }
}
