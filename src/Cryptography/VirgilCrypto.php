<?php

namespace Virgil\SDK\Cryptography;

use Virgil\Crypto\VirgilCipher;
use Virgil\SDK\Buffer;
use Virgil\SDK\BufferInterface;
use Virgil\SDK\Cryptography\CryptoAPI\Cipher\VirgilStreamCipher;
use Virgil\SDK\Cryptography\CryptoAPI\CryptoApiInterface;
use Virgil\SDK\Cryptography\CryptoAPI\VirgilCryptoApi;

class VirgilCrypto implements CryptoInterface
{
    private $cryptoApi;

    /**
     * VirgilCrypto constructor.
     * @param CryptoApiInterface $cryptoApi
     */
    public function __construct(CryptoApiInterface $cryptoApi = null)
    {
        $cryptoApi !== null ? $this->cryptoApi = $cryptoApi : $this->cryptoApi = new VirgilCryptoApi();
    }

    /**
     * @inheritdoc
     * @return VirgilKeyPair
     */
    public function generateKeys($cryptoType = KeyPairType::DefaultType)
    {
        $key = $this->cryptoApi->generate($cryptoType);
        $publicKeyDER = $this->cryptoApi->publicKeyToDER($key->getPublicKey());
        $privateKeyDER = $this->cryptoApi->privateKeyToDER($key->getPrivateKey());
        $publicKeyHash = $this->cryptoApi->computeHash($publicKeyDER, HashAlgorithm::DefaultType);

        return new VirgilKeyPair(
            new VirgilPublicKey($publicKeyHash, $publicKeyDER),
            new VirgilPrivateKey($publicKeyHash, $privateKeyDER)
        );
    }

    public function encrypt($data, $recipients)
    {
        /** @var VirgilCipher $cipher */
        $cipher = $this->cryptoApi->cipher();
        /** @var VirgilPublicKey $recipient */
        foreach ($recipients as $recipient) {
            $cipher->addKeyRecipient($recipient->getReceiverId()->getData(), $recipient->getValue()->getData());
        }
        return new Buffer($cipher->encrypt($data));
    }

    public function decrypt(BufferInterface $encryptedData, PrivateKeyInterface $privateKey)
    {
        /** @var VirgilCipher $cipher */
        $cipher = $this->cryptoApi->cipher();
        /** @var VirgilPrivateKey $privateKey */
        return new Buffer($cipher->decryptWithKey($encryptedData->getData(), $privateKey->getReceiverId()->getData(), $privateKey->getValue()->getData()));
    }

    public function streamEncrypt($source, $sin, $recipients)
    {
        /** @var VirgilStreamCipher $cipher */
        $cipher = $this->cryptoApi->streamCipher();
        /** @var VirgilPublicKey $recipient */
        foreach ($recipients as $recipient) {
            $cipher->addKeyRecipient($recipient->getReceiverId()->getData(), $recipient->getValue()->getData());
        }
        $cipher->encrypt($source, $sin);
    }

    public function streamDecrypt($source, $sin, PrivateKeyInterface $privateKey)
    {
        /** @var VirgilStreamCipher $cipher */
        $cipher = $this->cryptoApi->streamCipher();
        /** @var VirgilPrivateKey $privateKey */
        $cipher->decryptWithKey($source, $sin, $privateKey->getReceiverId()->getData(), $privateKey->getValue()->getData());
    }

    /**
     * @inheritdoc
     * @return Buffer
     */
    public function calculateFingerprint(BufferInterface $content)
    {
        return new Buffer($this->cryptoApi->computeHash($content->getData(), HashAlgorithm::DefaultType));
    }

    public function sign($content, PrivateKeyInterface $privateKey)
    {
        /** @var VirgilPrivateKey $privateKey */
        return new Buffer($this->cryptoApi->sign($content, $privateKey->getValue()->getData()));
    }

    public function verify($content, BufferInterface $signature, PublicKeyInterface $publicKey)
    {
        /** @var VirgilPublicKey $publicKey */
        return $this->cryptoApi->verify($content, $signature->getData(), $publicKey->getValue()->getData());
    }

    public function streamSign($source, PrivateKeyInterface $privateKey)
    {
        /** @var VirgilPrivateKey $privateKey */
        return new Buffer($this->cryptoApi->streamSign($source, $privateKey->getValue()->getData()));
    }

    public function streamVerify($source, BufferInterface $signature, PublicKeyInterface $publicKey)
    {
        /** @var VirgilPublicKey $publicKey */
        return $this->cryptoApi->streamVerify($source, $signature->getData(), $publicKey->getValue()->getData());
    }

    /**
     * @inheritdoc
     * @return VirgilPublicKey
     */
    public function extractPublicKey(PrivateKeyInterface $privateKey)
    {
        /** @var VirgilPrivateKey $privateKey */
        return new VirgilPublicKey($privateKey->getReceiverId()->getData(), $this->cryptoApi->extractPublicKey($privateKey->getValue()->getData(), ''));
    }

    public function exportPublicKey(PublicKeyInterface $publicKey)
    {
        /** @var VirgilPublicKey $publicKey */
        return new Buffer($this->cryptoApi->publicKeyToDER($publicKey->getValue()->getData()));
    }

    public function exportPrivateKey(PrivateKeyInterface $privateKey, $password = '')
    {
        /** @var VirgilPrivateKey $privateKey */
        return new Buffer($this->cryptoApi->privateKeyToDER($privateKey->getValue()->getData(), $password));
    }

    /**
     * @inheritdoc
     * @return VirgilPrivateKey
     */
    public function importPrivateKey(BufferInterface $privateKeyDER, $password = '')
    {
        if (strlen($password) === 0) {
            $privateKeyDERvalue = $this->cryptoApi->privateKeyToDER($privateKeyDER->getData());
        } else {
            $privateKeyDERvalue = $this->cryptoApi->decryptPrivateKey($privateKeyDER->getData(), $password);
        }

        return new VirgilPrivateKey(
            $this->cryptoApi->computeHash($this->cryptoApi->extractPublicKey($privateKeyDERvalue, ''), HashAlgorithm::DefaultType),
            $this->cryptoApi->privateKeyToDER($privateKeyDERvalue));
    }

    /**
     * @inheritdoc
     * @return VirgilPublicKey
     */
    public function importPublicKey(BufferInterface $exportedKey)
    {
        return new VirgilPublicKey(
            $this->cryptoApi->computeHash($exportedKey->getData(), HashAlgorithm::DefaultType),
            $this->cryptoApi->publicKeyToDER($exportedKey->getData())
        );
    }
}