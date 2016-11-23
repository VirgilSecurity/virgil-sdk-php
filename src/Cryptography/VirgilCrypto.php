<?php

namespace Virgil\SDK\Cryptography;

use Virgil\Crypto\VirgilCipher;
use Virgil\SDK\Buffer;
use Virgil\SDK\BufferInterface;
use Virgil\SDK\Contracts\CryptoInterface;
use Virgil\SDK\Contracts\PrivateKeyInterface;
use Virgil\SDK\Contracts\PublicKeyInterface;
use Virgil\SDK\Cryptography\CryptoAPI\Cipher\VirgilStreamCipher;
use Virgil\SDK\Cryptography\CryptoAPI\CryptoApiInterface;
use Virgil\SDK\Cryptography\CryptoAPI\VirgilCryptoApi;

class VirgilCrypto implements CryptoInterface
{
    use KeyStorageTrait;

    private $customParamKeySignature = 'VIRGIL-DATA-SIGNATURE';

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
        $keys = $this->cryptoApi->generate($cryptoType);
        $publicKeyDER = $this->cryptoApi->publicKeyToDER($keys->getPublicKey());
        $privateKeyDER = $this->cryptoApi->privateKeyToDER($keys->getPrivateKey());

        $publicKeyHash = new Buffer($this->cryptoApi->computeHash($publicKeyDER, HashAlgorithm::DefaultType));
        $privateKeyHash = new Buffer($this->cryptoApi->computeHash($privateKeyDER, HashAlgorithm::DefaultType));

        $publicKey = new VirgilPublicKey($publicKeyHash->toHex());
        $publicKeyEntry = new VirgilKeyEntry($publicKeyHash->getData(), $publicKeyDER);

        $privateKey = new VirgilPrivateKey($privateKeyHash->toHex());
        $privateKeyEntry = new VirgilKeyEntry($publicKeyHash->getData(), $privateKeyDER);

        $this->putKey($publicKey, $publicKeyEntry);
        $this->putKey($privateKey, $privateKeyEntry);

        return new VirgilKeyPair($publicKey, $privateKey);
    }

    public function encrypt($data, $recipients)
    {
        /** @var VirgilCipher $cipher */
        $cipher = $this->cryptoApi->cipher();
        /** @var VirgilPublicKey $recipient */
        foreach ($recipients as $recipient) {
            $keyEntry = $this->getKey($recipient);
            $cipher->addKeyRecipient($keyEntry->getReceiverId()->getData(), $keyEntry->getValue()->getData());
        }
        return new Buffer($cipher->encrypt($data));
    }

    public function decrypt(BufferInterface $encryptedData, PrivateKeyInterface $privateKey)
    {
        /** @var VirgilCipher $cipher */
        $cipher = $this->cryptoApi->cipher();
        /** @var VirgilPrivateKey $privateKey */
        $keyEntry = $this->getKey($privateKey);
        return new Buffer($cipher->decryptWithKey($encryptedData->getData(), $keyEntry->getReceiverId()->getData(), $keyEntry->getValue()->getData()));
    }

    public function streamEncrypt($source, $sin, $recipients)
    {
        /** @var VirgilStreamCipher $cipher */
        $cipher = $this->cryptoApi->streamCipher();
        /** @var VirgilPublicKey $recipient */
        foreach ($recipients as $recipient) {
            $keyEntry = $this->getKey($recipient);
            $cipher->addKeyRecipient($keyEntry->getReceiverId()->getData(), $keyEntry->getValue()->getData());
        }
        $cipher->encrypt($source, $sin);
    }

    public function streamDecrypt($source, $sin, PrivateKeyInterface $privateKey)
    {
        /** @var VirgilStreamCipher $cipher */
        $cipher = $this->cryptoApi->streamCipher();
        /** @var VirgilPrivateKey $privateKey */
        $keyEntry = $this->getKey($privateKey);
        $cipher->decryptWithKey($source, $sin, $keyEntry->getReceiverId()->getData(), $keyEntry->getValue()->getData());
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
        return new Buffer($this->cryptoApi->sign($content, $this->getKey($privateKey)->getValue()->getData()));
    }

    public function verify($content, BufferInterface $signature, PublicKeyInterface $publicKey)
    {
        /** @var VirgilPublicKey $publicKey */
        return $this->cryptoApi->verify($content, $signature->getData(), $this->getKey($publicKey)->getValue()->getData());
    }

    public function streamSign($source, PrivateKeyInterface $privateKey)
    {
        /** @var VirgilPrivateKey $privateKey */
        $keyEntry = $this->getKey($privateKey);
        return new Buffer($this->cryptoApi->streamSign($source, $keyEntry->getValue()->getData()));
    }

    public function streamVerify($source, BufferInterface $signature, PublicKeyInterface $publicKey)
    {
        /** @var VirgilPublicKey $publicKey */
        $keyEntry = $this->getKey($publicKey);
        return $this->cryptoApi->streamVerify($source, $signature->getData(), $keyEntry->getValue()->getData());
    }

    /**
     * @inheritdoc
     * @return VirgilPublicKey
     */
    public function extractPublicKey(PrivateKeyInterface $privateKey)
    {
        /** @var VirgilPrivateKey $privateKey */
        $privateKeyData = $this->getKey($privateKey);

        $publicKeyData = $this->cryptoApi->extractPublicKey($privateKeyData->getValue()->getData(), '');
        $publicKeyHash = new Buffer($this->cryptoApi->computeHash($publicKeyData, HashAlgorithm::DefaultType));

        $publicKey = new VirgilPublicKey($publicKeyHash->toHex());
        $keyEntry = new VirgilKeyEntry(
            $privateKeyData->getReceiverId()->getData(),
            $publicKeyData
        );

        $this->putKey($publicKey, $keyEntry);

        return $publicKey;
    }

    public function exportPublicKey(PublicKeyInterface $publicKey)
    {
        /** @var VirgilPublicKey $publicKey */
        $keyEntry = $this->getKey($publicKey);
        return new Buffer($this->cryptoApi->publicKeyToDER($keyEntry->getValue()->getData()));
    }

    public function exportPrivateKey(PrivateKeyInterface $privateKey, $password = '')
    {
        /** @var VirgilPrivateKey $privateKey */
        $keyEntry = $this->getKey($privateKey);
        return new Buffer($this->cryptoApi->privateKeyToDER($keyEntry->getValue()->getData(), $password));
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

        $privateKeyHash = new Buffer($this->cryptoApi->computeHash($privateKeyDERvalue, HashAlgorithm::DefaultType));
        $privateKey = new VirgilPrivateKey(
            $privateKeyHash->toHex()
        );
        $keyEntry = new VirgilKeyEntry(
            $this->cryptoApi->computeHash($this->cryptoApi->extractPublicKey($privateKeyDERvalue, ''), HashAlgorithm::DefaultType),
            $this->cryptoApi->privateKeyToDER($privateKeyDERvalue)
        );

        $this->putKey($privateKey, $keyEntry);

        return $privateKey;
    }

    /**
     * @inheritdoc
     * @return VirgilPublicKey
     */
    public function importPublicKey(BufferInterface $exportedKey)
    {
        $hash = new Buffer($this->cryptoApi->computeHash($exportedKey->getData(), HashAlgorithm::DefaultType));
        $publicKey = new VirgilPublicKey($hash->toHex());
        $keyEntry = new VirgilKeyEntry(
            $hash->getData(),
            $this->cryptoApi->publicKeyToDER($exportedKey->getData())
        );

        $this->putKey($publicKey, $keyEntry);

        return $publicKey;
    }

    public function signThenEncrypt($data, PrivateKeyInterface $privateKey, $recipients)
    {
        /** @var VirgilPrivateKey $privateKey */
        $signature = $this->cryptoApi->sign($data, $this->getKey($privateKey)->getValue()->getData());
        $cipher = $this->cryptoApi->cipher();
        $cipher->customParams()->setData($this->customParamKeySignature, $signature);
        /** @var VirgilPublicKey $recipient */
        foreach ($recipients as $recipient) {
            $keyEntry = $this->getKey($recipient);
            $cipher->addKeyRecipient($keyEntry->getReceiverId()->getData(), $keyEntry->getValue()->getData());
        }
        return new Buffer($cipher->encrypt($data));
    }

    public function decryptThenVerify(BufferInterface $encryptedData, PrivateKeyInterface $privateKey, PublicKeyInterface $publicKey)
    {
        $cipher = $this->cryptoApi->cipher();
        /** @var VirgilPrivateKey $privateKey */
        $privateKeyEntry = $this->getKey($privateKey);
        /** @var VirgilPrivateKey $publicKey */
        $publicKeyEntry = $this->getKey($publicKey);
        $decryptedData = $cipher->decryptWithKey($encryptedData->getData(), $privateKeyEntry->getReceiverId()->getData(), $privateKeyEntry->getValue()->getData());
        $signature = $cipher->customParams()->getData($this->customParamKeySignature);
        if (!$this->cryptoApi->verify($decryptedData, $signature, $publicKeyEntry->getValue()->getData())) {
            throw new SignatureIsNotValidException();
        }
        return new Buffer($decryptedData);
    }
}