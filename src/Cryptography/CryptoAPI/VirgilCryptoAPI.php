<?php

namespace Virgil\SDK\Cryptography\CryptoAPI;


use Virgil\Crypto\VirgilHash;
use Virgil\Crypto\VirgilKeyPair as LibraryKeyPair;
use Virgil\SDK\Cryptography\CryptoAPI\Exceptions\ComputePublicKeyHashException;
use Virgil\SDK\Cryptography\CryptoAPI\Exceptions\DecryptPrivateKeyException;
use Virgil\SDK\Cryptography\CryptoAPI\Exceptions\EncryptPrivateKeyException;
use Virgil\SDK\Cryptography\CryptoAPI\Exceptions\ExtractPublicKeyException;
use Virgil\SDK\Cryptography\CryptoAPI\Exceptions\GenerateException;
use Virgil\SDK\Cryptography\CryptoAPI\Exceptions\IsKeyPairMatchException;
use Virgil\SDK\Cryptography\CryptoAPI\Exceptions\PrivateKeyToDERException;
use Virgil\SDK\Cryptography\CryptoAPI\Exceptions\PublicKeyToDERException;

class VirgilCryptoAPI implements CryptoAPI
{
    /**
     * @param integer $type Key generation type
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
     * @param string $key
     * @return mixed
     * @throws PrivateKeyToDERException
     */
    public function privateKeyToDER($key)
    {
        try {
            return LibraryKeyPair::privateKeyToDER($key);
        } catch (\Exception $e) {
            throw new PrivateKeyToDERException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $key
     * @return mixed
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
     * @param string $publicKey
     * @param string $privateKey
     * @return mixed
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
     * @param mixed $keyDER DER public key value
     * @param integer $algorithm Hash algorithm
     * @return mixed
     * @throws ComputePublicKeyHashException
     */
    public function computeKeyHash($keyDER, $algorithm)
    {
        try {
            $virgilHash = new VirgilHash($algorithm);
            return $virgilHash->hash($keyDER);
        } catch (\Exception $e) {
            throw new ComputePublicKeyHashException($e->getMessage(), $e->getCode());
        }
    }


    /**
     * @param string $privateKey
     * @param string $password
     * @return string
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
     * @param string $privateKey
     * @param string $password
     * @return string
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
     * @param string $privateKey
     * @param string $password
     * @return string
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

}