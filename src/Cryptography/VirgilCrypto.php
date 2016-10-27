<?php

namespace Virgil\SDK\Cryptography;


use Virgil\SDK\Cryptography\CryptoAPI\Cipher\VirgilCipher;
use Virgil\SDK\Cryptography\CryptoAPI\Cipher\VirgilStreamCipher;
use Virgil\SDK\Cryptography\CryptoAPI\CryptoApiInterface;

class VirgilCrypto implements CryptoInterface
{
    private $cryptoAPI;

    /**
     * VirgilCrypto constructor.
     * @param CryptoApiInterface $cryptoApi
     */
    public function __construct(CryptoApiInterface $cryptoApi)
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
        $publicKeyHash = $this->calculateFingerprint($publicKeyDER);

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


    public function calculateFingerprint($content)
    {
        return $this->cryptoAPI->computeKeyHash($content, VirgilHashAlgorithmType::DefaultType);
    }


    public function sign($content, KeyInterface $privateKey)
    {
        return $this->cryptoAPI->sign($content, $privateKey->getValue());
    }

    public function verify($content, $signature, KeyInterface $publicKey)
    {
        return $this->cryptoAPI->verify($content, $signature, $publicKey->getValue());
    }


    public function streamSign($source, KeyInterface $privateKey)
    {
        return $this->cryptoAPI->streamSign($source, $privateKey->getValue());
    }

    public function streamVerify($source, $signature, KeyInterface $publicKey)
    {
        return $this->cryptoAPI->streamVerify($source, $signature, $publicKey->getValue());
    }

    /**
     * @param KeyInterface $privateKey
     * @return VirgilKey
     */
    public function extractPublicKey(KeyInterface $privateKey)
    {
        return new VirgilKey($privateKey->getReceiverId(), $this->cryptoAPI->extractPublicKey($privateKey->getValue(), ''));
    }

    public function exportPublicKey(KeyInterface $publicKey)
    {
        return $this->cryptoAPI->publicKeyToDER($publicKey->getValue());
    }

    public function exportPrivateKey(KeyInterface $privateKey, $password = '')
    {
        return $this->cryptoAPI->privateKeyToDER($privateKey->getValue(), $password);
    }

    /**
     * @inheritdoc
     * @return VirgilKey
     */
    public function importPrivateKey($privateKeyDERvalue, $password = '')
    {
        if (strlen($password) === 0) {
            $privateKeyDERvalue = $this->cryptoAPI->privateKeyToDER($privateKeyDERvalue);
        } else {
            $privateKeyDERvalue = $this->cryptoAPI->decryptPrivateKey($privateKeyDERvalue, $password);
        }

        return new VirgilKey(
            $this->calculateFingerprint($this->cryptoAPI->extractPublicKey($privateKeyDERvalue, '')),
            $this->cryptoAPI->privateKeyToDER($privateKeyDERvalue));
    }

    /**
     * @inheritdoc
     * @return VirgilKey
     */
    public function importPublicKey($exportedKey)
    {
        return new VirgilKey(
            $this->calculateFingerprint($exportedKey),
            $this->cryptoAPI->publicKeyToDER($exportedKey)
        );
    }
}