<?php

namespace Virgil\Sdk\Cryptography\Core;


use Exception;

use Virgil\Sdk\Cryptography\Core\Exceptions;

use Virgil\Sdk\Cryptography\Core\Cipher\VirgilCipher;
use Virgil\Sdk\Cryptography\Core\Cipher\VirgilStreamCipher;
use Virgil\Sdk\Cryptography\Core\Cipher\VirgilStreamDataSource;

use Virgil\Sdk\Cryptography\Core\Exceptions\ContentSigningException;
use Virgil\Sdk\Cryptography\Core\Exceptions\ContentVerificationException;
use Virgil\Sdk\Cryptography\Core\Exceptions\InvalidKeyPairException;
use Virgil\Sdk\Cryptography\Core\Exceptions\KeyPairGenerationException;
use Virgil\Sdk\Cryptography\Core\Exceptions\PrivateKeyDecryptionException;
use Virgil\Sdk\Cryptography\Core\Exceptions\PrivateKeyEncryptionException;
use Virgil\Sdk\Cryptography\Core\Exceptions\PrivateKeyToDerConvertingException;
use Virgil\Sdk\Cryptography\Core\Exceptions\PublicKeyExtractionException;
use Virgil\Sdk\Cryptography\Core\Exceptions\PublicKeyHashComputationException;
use Virgil\Sdk\Cryptography\Core\Exceptions\PublicKeyToDerConvertingException;


use VirgilCipher as CryptoVirgilCipher;
use VirgilChunkCipher as CryptoVirgilChunkCipher;
use VirgilSigner as CryptoVirgilSigner;
use VirgilStreamSigner as CryptoVirgilStreamSigner;
use VirgilKeyPair as CryptoVirgilKeyPair;
use VirgilHash as CryptoVirgilHash;

/**
 * Class aims to wrap native crypto library and provides cryptographic operations.
 */
class VirgilCryptoService implements CryptoServiceInterface
{
    /**
     * @inheritdoc
     *
     * @return VirgilKeyPair
     * @throws KeyPairGenerationException
     */
    public function generateKeyPair($keyPairType)
    {
        try {
            $keyPair = CryptoVirgilKeyPair::generate($keyPairType);

            return new VirgilKeyPair($keyPair->publicKey(), $keyPair->privateKey());
        } catch (Exception $exception) {
            throw new KeyPairGenerationException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws PrivateKeyToDerConvertingException
     */
    public function privateKeyToDer($privateKey, $privateKyePassword = '')
    {
        try {
            if (strlen($privateKyePassword) === 0) {
                return CryptoVirgilKeyPair::privateKeyToDER($privateKey);
            }

            return CryptoVirgilKeyPair::privateKeyToDER(
                $this->encryptPrivateKey($privateKey, $privateKyePassword),
                $privateKyePassword
            );
        } catch (Exception $exception) {
            throw new PrivateKeyToDerConvertingException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws PublicKeyToDerConvertingException
     */
    public function publicKeyToDer($publicKey)
    {
        try {
            return CryptoVirgilKeyPair::publicKeyToDER($publicKey);
        } catch (Exception $exception) {
            throw new PublicKeyToDerConvertingException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws InvalidKeyPairException
     */
    public function isKeyPair($publicKey, $privateKey)
    {
        try {
            return CryptoVirgilKeyPair::isKeyPairMatch($publicKey, $privateKey);
        } catch (Exception $exception) {
            throw new InvalidKeyPairException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws PublicKeyHashComputationException
     */
    public function computeHash($publicKeyDER, $hashAlgorithm)
    {
        try {
            return (new CryptoVirgilHash($hashAlgorithm))->hash($publicKeyDER);
        } catch (Exception $exception) {
            throw new PublicKeyHashComputationException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws PublicKeyExtractionException
     */
    public function extractPublicKey($privateKey, $privateKeyPassword)
    {
        try {
            return CryptoVirgilKeyPair::extractPublicKey($privateKey, $privateKeyPassword);
        } catch (Exception $exception) {
            throw new PublicKeyExtractionException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws PrivateKeyEncryptionException
     */
    public function encryptPrivateKey($privateKey, $password)
    {
        try {
            return CryptoVirgilKeyPair::encryptPrivateKey($privateKey, $password);
        } catch (Exception $exception) {
            throw new PrivateKeyEncryptionException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws PrivateKeyDecryptionException
     */
    public function decryptPrivateKey($privateKey, $privateKeyPassword)
    {
        try {
            return CryptoVirgilKeyPair::decryptPrivateKey($privateKey, $privateKeyPassword);
        } catch (Exception $exception) {
            throw new PrivateKeyDecryptionException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws ContentSigningException
     */
    public function sign($content, $privateKey)
    {
        try {
            return (new CryptoVirgilSigner())->sign($content, $privateKey);
        } catch (Exception $exception) {
            throw new ContentSigningException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @throws ContentVerificationException
     */
    public function verify($content, $signature, $publicKey)
    {
        try {
            return (new CryptoVirgilSigner())->verify($content, $signature, $publicKey);
        } catch (Exception $exception) {
            throw new ContentVerificationException($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * @inheritdoc
     *
     * @return VirgilCipher
     */
    public function createCipher()
    {
        return new VirgilCipher(new CryptoVirgilCipher());
    }


    /**
     * @inheritdoc
     *
     * @return VirgilStreamCipher
     */
    public function createStreamCipher()
    {
        return new VirgilStreamCipher(new CryptoVirgilChunkCipher());
    }


    /**
     * @inheritdoc
     *
     * @throws ContentSigningException
     */
    public function signStream($stream, $privateKey)
    {
        try {
            $virgilSourceStream = new VirgilStreamDataSource($stream);
            $virgilSourceStream->reset();

            return (new CryptoVirgilStreamSigner())->sign($virgilSourceStream, $privateKey);
        } catch (Exception $exception) {
            throw new ContentSigningException($exception->getMessage(), $exception->getCode());
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

            return (new CryptoVirgilStreamSigner())->verify($virgilSourceStream, $signature, $publicKey);
        } catch (Exception $exception) {
            throw new ContentVerificationException($exception->getMessage(), $exception->getCode());
        }
    }
}
