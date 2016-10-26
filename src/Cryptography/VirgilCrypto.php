<?php

namespace Virgil\SDK\Cryptography;


use Virgil\SDK\Cryptography\CryptoAPI\Cipher\VirgilCipher;
use Virgil\SDK\Cryptography\CryptoAPI\Cipher\VirgilStreamCipher;
use Virgil\SDK\Cryptography\CryptoAPI\CryptoAPI;

class VirgilCrypto implements CryptoInterface
{
    private $cryptoAPI;

    /**
     * VirgilCrypto constructor.
     * @param CryptoAPI $cryptoApi
     */
    public function __construct(CryptoAPI $cryptoApi)
    {
        $this->cryptoAPI = $cryptoApi;
    }

    /**
     * @inheritdoc
     * @return VirgilKeyPair
     */
    public function generateKeys($cryptoType = VirgilCryptoType::DefaultType)
    {
        $key = $this->cryptoAPI->generate($cryptoType);
        $publicKeyDER = $this->cryptoAPI->publicKeyToDER($key->getPublicKey());
        $privateKeyDER = $this->cryptoAPI->privateKeyToDER($key->getPrivateKey());
        $publicKeyHash = $this->cryptoAPI->computeKeyHash($publicKeyDER, VirgilHashAlgorithmType::DefaultType);

        return new VirgilKeyPair(
            new VirgilKey($publicKeyHash, $publicKeyDER),
            new VirgilKey($publicKeyHash, $privateKeyDER)
        );
    }

    public function encrypt($data, $recipients)
    {
        /** @var VirgilCipher $cipher */
        $cipher = $this->cryptoAPI->cipher();
        foreach ($recipients as $recipient) {
            $cipher->addKeyRecipient($recipient->getReceiverId(), $recipient->getValue());
        }
        return $cipher->encrypt($data);
    }

    public function decrypt($encryptedData, KeyInterface $privateKey)
    {
        /** @var VirgilCipher $cipher */
        $cipher = $this->cryptoAPI->cipher();
        return $cipher->decryptWithKey($encryptedData, $privateKey->getReceiverId(), $privateKey->getValue());
    }

    public function streamEncrypt($source, $sin, $recipients)
    {
        /** @var VirgilStreamCipher $cipher */
        $cipher = $this->cryptoAPI->streamCipher();
        /** @var KeyInterface $recipient */
        foreach ($recipients as $recipient) {
            $cipher->addKeyRecipient($recipient->getReceiverId(), $recipient->getValue());
        }
        $cipher->encrypt($source, $sin);
    }

    public function streamDecrypt($source, $sin, KeyInterface $privateKey)
    {
        /** @var VirgilStreamCipher $cipher */
        $cipher = $this->cryptoAPI->streamCipher();
        $cipher->decryptWithKey($source, $sin, $privateKey->getReceiverId(), $privateKey->getValue());
    }
}