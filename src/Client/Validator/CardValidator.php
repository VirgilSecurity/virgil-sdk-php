<?php
namespace Virgil\Sdk\Client\Validator;


use Exception;

use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Card;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

/**
 * Class verifies card signatures. By default validator checks service and card owner signatures only, but its possible
 * to attach more public keys by signature id for other signatures validation.
 */
class CardValidator implements CardValidatorInterface
{
    const SERVICE_CARD_ID = '3e29d43373348cfb373b7eae189214dc01d7237765e572db685839b64adca853';
    const SERVICE_PUBLIC_KEY = 'LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUNvd0JRWURLMlZ3QXlFQVlSNTAxa1YxdFVuZTJ1T2RrdzRrRXJSUmJKcmMyU3lhejVWMWZ1RytyVnM9Ci0tLS0tRU5EIFBVQkxJQyBLRVktLS0tLQo=';

    /** @var CryptoInterface $crypto */
    private $crypto;

    /** @var array $signsVerifiers */
    private $signsVerifiers = [];


    /**
     * Class constructor.
     *
     * @param CryptoInterface $crypto
     */
    public function __construct(CryptoInterface $crypto)
    {
        $this->crypto = $crypto;
        $publicKey = $crypto->importPublicKey(Buffer::fromBase64(self::SERVICE_PUBLIC_KEY));
        $this->addVerifier(self::SERVICE_CARD_ID, $publicKey);
    }


    /**
     * @inheritdoc
     */
    public function validate(Card $card)
    {
        $fingerprint = $this->crypto->calculateFingerprint($card->getSnapshot());
        $fingerprintHex = $fingerprint->toHex();
        $exceptionMessage = 'Card signs with id ' . $card->getId() . ' are invalid.';

        $cardValidationException = new CardValidationException($exceptionMessage);

        if ($fingerprintHex != $card->getId()) {
            throw $cardValidationException;
        }

        $this->addVerifier($fingerprintHex, $this->crypto->importPublicKey($card->getPublicKeyData()));

        foreach ($this->signsVerifiers as $signId => $signerPublicKey) {

            if (!array_key_exists($signId, $card->getSignatures())) {
                throw $cardValidationException;
            }

            try {
                $this->crypto->verify(
                    $fingerprint->getData(),
                    $card->getSignature($signId),
                    $signerPublicKey
                );
            } catch (Exception $exception) {
                throw $cardValidationException;
            }
        }

        return $this;
    }


    /**
     * Add public key to verification list.
     *
     * @param string             $signId
     * @param PublicKeyInterface $signerPublicKey
     */
    public function addVerifier($signId, PublicKeyInterface $signerPublicKey)
    {
        $this->signsVerifiers[$signId] = $signerPublicKey;
    }
}
