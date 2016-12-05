<?php

namespace Virgil\Sdk\Cryptography\CryptoAPI;


use Virgil\Crypto\VirgilHash;
use Virgil\Crypto\VirgilSigner;
use Virgil\Crypto\VirgilStreamSigner;
use Virgil\Sdk\Cryptography\CryptoAPI\Cipher\VirgilCipher;
use Virgil\Sdk\Cryptography\CryptoAPI\Cipher\VirgilStreamCipher;
use Virgil\Sdk\Cryptography\CryptoAPI\Cipher\VirgilStreamDataSource;
use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\ComputePublicKeyHashException;
use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\DecryptPrivateKeyException;
use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\EncryptPrivateKeyException;
use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\ExtractPublicKeyException;
use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\GenerateException;
use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\IsKeyPairMatchException;
use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\PrivateKeyToDERException;
use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\PublicKeyToDERException;
use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\SignException;
use Virgil\Sdk\Cryptography\CryptoAPI\Exceptions\VerifyException;
use Virgil\Crypto\VirgilKeyPair as LibraryKeyPair;
use Virgil\Crypto\VirgilChunkCipher as LibraryChunkCipher;
use Virgil\Crypto\VirgilCipher as LibraryCipher;

class VirgilCryptoApi implements CryptoApiInterface
{
    /**
     * @inheritdoc
     * @return VirgilKeyPair
     * @throws GenerateException
     */
    public function generate($type)
    {
        try {
            $keys = LibraryKeyPair::generate($type);

            return new VirgilKeyPair($keys->publicKey(), $keys->privateKey());
        } catch (\Exception $e) {
            throw new GenerateException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @inheritdoc
     * @throws PrivateKeyToDERException
     */
    public function privateKeyToDER($key, $password = '')
    {
        try {
            if (strlen($password) === 0) {
                return LibraryKeyPair::privateKeyToDER($key);
            }
            return LibraryKeyPair::privateKeyToDER($this->encryptPrivateKey($key, $password), $password);
        } catch (\Exception $e) {
            throw new PrivateKeyToDERException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @inheritdoc
     * @throws PublicKeyToDERException
     */
    public function publicKeyToDER($key)
    {
        try {
            return LibraryKeyPair::publicKeyToDER($key);
        } catch (\Exception $e) {
            throw new PublicKeyToDERException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @inheritdoc
     * @throws IsKeyPairMatchException
     */
    public function isKeyPairMatch($publicKey, $privateKey)
    {
        try {
            return LibraryKeyPair::isKeyPairMatch($publicKey, $privateKey);
        } catch (\Exception $e) {
            throw new IsKeyPairMatchException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @inheritdoc
     * @throws ComputePublicKeyHashException
     */
    public function computeHash($keyDER, $algorithm)
    {
        try {
            $virgilHash = new VirgilHash($algorithm);
            return $virgilHash->hash($keyDER);
        } catch (\Exception $e) {
            throw new ComputePublicKeyHashException($e->getMessage(), $e->getCode());
        }
    }


    /**
     * @inheritdoc
     * @throws ExtractPublicKeyException
     */
    public function extractPublicKey($privateKey, $password)
    {
        try {
            return LibraryKeyPair::extractPublicKey($privateKey, $password);
        } catch (\Exception $e) {
            throw new ExtractPublicKeyException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @inheritdoc
     * @throws EncryptPrivateKeyException
     */
    public function encryptPrivateKey($privateKey, $password)
    {
        try {
            return LibraryKeyPair::encryptPrivateKey($privateKey, $password);
        } catch (\Exception $e) {
            throw new EncryptPrivateKeyException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @inheritdoc
     * @throws DecryptPrivateKeyException
     */
    public function decryptPrivateKey($privateKey, $password)
    {
        try {
            return LibraryKeyPair::decryptPrivateKey($privateKey, $password);
        } catch (\Exception $e) {
            throw new DecryptPrivateKeyException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @inheritdoc
     * @throws SignException
     */
    public function sign($data, $privateKey)
    {
        try {
            $signer = new VirgilSigner();
            return $signer->sign($data, $privateKey);
        } catch (\Exception $e) {
            throw new SignException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @inheritdoc
     * @throws VerifyException
     */
    public function verify($data, $signature, $publicKey)
    {
        try {
            $signer = new VirgilSigner();
            return $signer->verify($data, $signature, $publicKey);
        } catch (\Exception $e) {
            throw new VerifyException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return VirgilCipher
     */
    public function cipher()
    {
        return new VirgilCipher(new LibraryCipher());
    }

    /**
     * @return VirgilStreamCipher
     */
    public function streamCipher()
    {
        return new VirgilStreamCipher(new LibraryChunkCipher());
    }

    /**
     * @inheritdoc
     * @throws SignException
     */
    public function streamSign($stream, $privateKey)
    {
        try {
            $signer = new VirgilStreamSigner();
            $dataStream = new VirgilStreamDataSource($stream);
            $dataStream->reset();
            return $signer->sign($dataStream, $privateKey);
        } catch (\Exception $e) {
            throw new SignException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @inheritdoc
     * @throws VerifyException
     */
    public function streamVerify($stream, $signature, $publicKey)
    {
        try {
            $signer = new VirgilStreamSigner();
            $dataStream = new VirgilStreamDataSource($stream);
            return $signer->verify($dataStream, $signature, $publicKey);
        } catch (\Exception $e) {
            throw new VerifyException($e->getMessage(), $e->getCode());
        }
    }
}
