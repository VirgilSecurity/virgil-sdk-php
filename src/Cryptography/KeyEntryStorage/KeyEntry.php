<?php
namespace Virgil\Sdk\Cryptography\KeyEntryStorage;


/**
 * Class provides access to DER encoded key value and recipient id for its key.
 */
class KeyEntry
{
    protected $recipientId;
    protected $value;


    /**
     * Class constructor.
     *
     * @param string $recipientId        recipient id for current key
     * @param string $derEncodedKeyValue DER encoded key value
     */
    public function __construct($recipientId, $derEncodedKeyValue)
    {
        $this->recipientId = $recipientId;
        $this->value = $derEncodedKeyValue;
    }


    /**
     * @return string
     */
    public function getRecipientId()
    {
        return $this->recipientId;
    }


    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
