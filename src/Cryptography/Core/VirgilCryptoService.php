<?php

namespace Virgil\Sdk\Cryptography\Core;


use Exception;
use Virgil\Crypto as VirgilCrypto;
use Virgil\Sdk\Cryptography\Core\Exceptions;

use Virgil\Sdk\Cryptography\Core\Cipher\VirgilCipher;
use Virgil\Sdk\Cryptography\Core\Cipher\VirgilStreamCipher;
use Virgil\Sdk\Cryptography\Core\Cipher\VirgilStreamDataSource;

/**
 * Class aims to wrap native crypto library and provides cryptographic operations.
 */
class VirgilCryptoService implements CryptoServiceInterface
{
    /**
     * @inheritdoc
     *
     * @return VirgilKeyPair
     * @throws Exceptions\KeyPairGenerationException
     */
    public function generateKeyPair($keyPairType)
    {
        try {
            $keyPair = VirgilCrypto\VirgilKeyPair::generate($keyPairType);

            return new VirgilKeyPair($keyPair->publicKey(), $keyPair->privateKey());
        } catch (Exception $exception) {
            throw new Exceptions\KeyPairGenerationException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\PrivateKeyToDerConvertingException
     */
    public function privateKeyToDer($privateKey, $privateKyePassword = '')
    {
        try {
            if (strlen($privateKyePassword) === 0) {
                return VirgilCrypto\VirgilKeyPair::privateKeyToDER($privateKey);
            }

            return VirgilCrypto\VirgilKeyPair::privateKeyToDER(
                $this->encryptPrivateKey($privateKey, $privateKyePassword),
                $privateKyePassword
            );
        } catch (Exception $exception) {
            throw new Exceptions\PrivateKeyToDerConvertingException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\PublicKeyToDerConvertingException
     */
    public function publicKeyToDer($publicKey)
    {
        try {
            return VirgilCrypto\VirgilKeyPair::publicKeyToDER($publicKey);
        } catch (Exception $exception) {
            throw new Exceptions\PublicKeyToDerConvertingException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\InvalidKeyPairException
     */
    public function isKeyPair($publicKey, $privateKey)
    {
        try {
            return VirgilCrypto\VirgilKeyPair::isKeyPairMatch($publicKey, $privateKey);
        } catch (Exception $exception) {
            throw new Exceptions\InvalidKeyPairException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\PublicKeyHashComputationException
     */
    public function computeHash($publicKeyDER, $hashAlgorithm)
    {
        try {
            return (new VirgilCrypto\VirgilHash($hashAlgorithm))->hash($publicKeyDER);
        } catch (Exception $exception) {
            throw new Exceptions\PublicKeyHashComputationException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\PublicKeyExtractionException
     */
    public function extractPublicKey($privateKey, $privateKeyPassword)
    {
        try {
            return VirgilCrypto\VirgilKeyPair::extractPublicKey($privateKey, $privateKeyPassword);
        } catch (Exception $exception) {
            throw new Exceptions\PublicKeyExtractionException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\PrivateKeyEncryptionException
     */
    public function encryptPrivateKey($privateKey, $password)
    {
        try {
            return VirgilCrypto\VirgilKeyPair::encryptPrivateKey($privateKey, $password);
        } catch (Exception $exception) {
            throw new Exceptions\PrivateKeyEncryptionException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\PrivateKeyDecryptionException
     */
    public function decryptPrivateKey($privateKey, $privateKeyPassword)
    {
        try {
            return VirgilCrypto\VirgilKeyPair::decryptPrivateKey($privateKey, $privateKeyPassword);
        } catch (Exception $exception) {
            throw new Exceptions\PrivateKeyDecryptionException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\ContentSigningException
     */
    public function sign($content, $privateKey)
    {
        try {
            return (new VirgilCrypto\VirgilSigner())->sign($content, $privateKey);
        } catch (Exception $exception) {
            throw new Exceptions\ContentSigningException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\ContentVerificationException
     */
    public function verify($content, $signature, $publicKey)
    {
        try {
            return (new VirgilCrypto\VirgilSigner())->verify($content, $signature, $publicKey);
        } catch (Exception $exception) {
            throw new Exceptions\ContentVerificationException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @return VirgilCipher
     */
    public function createCipher()
    {
        return new VirgilCipher(new VirgilCrypto\VirgilCipher());
    }


    /**
     * @inheritdoc
     *
     * @return VirgilStreamCipher
     */
    public function createStreamCipher()
    {
        return new VirgilStreamCipher(new VirgilCrypto\VirgilChunkCipher());
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\ContentSigningException
     */
    public function signStream($stream, $privateKey)
    {
        try {
            $virgilSourceStream = new VirgilStreamDataSource($stream);
            $virgilSourceStream->reset();

            return (new VirgilCrypto\VirgilStreamSigner())->sign($virgilSourceStream, $privateKey);
        } catch (Exception $exception) {
            throw new Exceptions\ContentSigningException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws Exceptions\ContentVerificationException
     */
    public function verifyStream($stream, $signature, $publicKey)
    {
        try {
            $virgilSourceStream = new VirgilStreamDataSource($stream);

            return (new VirgilCrypto\VirgilStreamSigner())->verify($virgilSourceStream, $signature, $publicKey);
        } catch (Exception $exception) {
            throw new Exceptions\ContentVerificationException($exception->getMessage(), $exception->getCode());
        }
    }
}
