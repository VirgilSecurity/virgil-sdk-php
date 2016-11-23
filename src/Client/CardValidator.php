<?php

namespace Virgil\SDK\Client;


use Virgil\SDK\Buffer;
use Virgil\SDK\BufferInterface;
use Virgil\SDK\Contracts\CryptoInterface;

class CardValidator implements CardValidatorInterface
{
    private $crypto;
    private $verifiers = [];
    private $serviceCardId = '3e29d43373348cfb373b7eae189214dc01d7237765e572db685839b64adca853';
    private $servicePublicKey = 'LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUNvd0JRWURLMlZ3QXlFQVlSNTAxa1YxdFVuZTJ1T2RrdzRrRXJSUmJKcmMyU3lhejVWMWZ1RytyVnM9Ci0tLS0tRU5EIFBVQkxJQyBLRVktLS0tLQo=';

    /**
     * CardValidator constructor.
     *
     * @param CryptoInterface $crypto
     */
    public function __construct(CryptoInterface $crypto)
    {
        $this->crypto = $crypto;
        $this->addVerifier($this->serviceCardId, Buffer::fromBase64($this->servicePublicKey));
    }


    public function validate(Card $card)
    {
        $fingerprint = $this->crypto->calculateFingerprint($card->getSnapshot());
        $fingerprintHex = $fingerprint->toHex();

        if ($fingerprintHex != $card->getId()) {
            return false;
        }

        $this->addVerifier($fingerprintHex, $card->getPublicKey());

        foreach ($this->verifiers as $verifierKey => $verifier) {

            if (!array_key_exists($verifierKey, $card->getSignatures())) {
                return false;
            }

            try {
                $isValid = $this->crypto->verify($fingerprint->getData(), $card->getSignature($verifierKey), $verifier);
            } catch (\Exception $exception) {
                $isValid = false;
            }

            if (!$isValid) {
                return false;
            }
        }

        return true;
    }

    /**
     * Add verifier to verification list.
     *
     * @param string $verifierId
     * @param BufferInterface $verifierPublicKey
     */
    public function addVerifier($verifierId, BufferInterface $verifierPublicKey)
    {
        $this->verifiers[$verifierId] = $this->crypto->importPublicKey($verifierPublicKey);
    }
}