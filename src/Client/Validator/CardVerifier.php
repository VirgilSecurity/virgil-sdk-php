<?php
namespace Virgil\Sdk\Client\Validator;


use Virgil\Sdk\Contracts\BufferInterface;


/**
 * @inheritdoc
 */
class CardVerifier implements CardVerifierInfoInterface
{
    /** @var string */
    private $cardId;

    /** @var BufferInterface */
    private $publicKeyData;


    /**
     * Class constructor.
     *
     * @param string          $cardId
     * @param BufferInterface $publicKeyData
     */
    public function __construct($cardId, BufferInterface $publicKeyData)
    {
        $this->cardId = $cardId;
        $this->publicKeyData = $publicKeyData;
    }


    /**
     * @inheritdoc
     */
    public function getCardId()
    {
        return $this->cardId;
    }


    /**
     * @inheritdoc
     */
    public function getPublicKeyData()
    {
        return $this->publicKeyData;
    }
}
