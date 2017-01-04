<?php
namespace Virgil\Sdk\Cryptography;


use Virgil\Sdk\Buffer;
use Virgil\Sdk\BufferInterface;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

use Virgil\Sdk\Cryptography\Constants\HashAlgorithms;
use Virgil\Sdk\Cryptography\Constants\KeyPairTypes;

use Virgil\Sdk\Cryptography\Core\Cipher\InputOutputInterface;
use Virgil\Sdk\Cryptography\Core\Cipher\CipherInterface;

use Virgil\Sdk\Cryptography\Core\CryptoServiceInterface;
use Virgil\Sdk\Cryptography\Core\VirgilCryptoService;

use Virgil\Sdk\Cryptography\KeyEntryStorage\KeyEntry;
use Virgil\Sdk\Cryptography\KeyEntryStorage\KeyEntryStorageTrait;

/**
 * Class provides a cryptographic operations in applications, such as hashing,
 * signature generation and verification, and encryption and decryption.
 *
 * In most cases crypto operations like encrypt\decrypt or sign\verify need crypto keys.
 * Thus class also responsible for generation or exporting and importing such keys to perform crypto operations or
 * it is possible extract public from private key by using appropriate method.
 *
 */
class VirgilCrypto implements CryptoInterface
{
    use KeyEntryStorageTrait;

    const CUSTOM_PARAM_KEY_SIGNATURE = 'VIRGIL-DATA-SIGNATURE';

    /** @var VirgilCryptoService $cryptoService */
    private $cryptoService;


    /**
     * Class constructor.
     *
     * @param CryptoServiceInterface $cryptoService
     */
    public function __construct(CryptoServiceInterface $cryptoService = null)
    {
        if ($cryptoService === null) {
            $cryptoService = new VirgilCryptoService();
        }

        $this->cryptoService = $cryptoService;
    }


    /**
     * Generates the public\private key pair by specific crypto type.
     *
     * @param int $keyPairType is one of key pair type constant
     *
     * @return VirgilKeyPair
     */
    public function generateKeys($keyPairType = KeyPairTypes::FAST_EC_ED25519)
    {
        $keyPair = $this->cryptoService->generateKeyPair($keyPairType);

        $publicKeyDerEncoded = $this->cryptoService->publicKeyToDer($keyPair->getPublicKey());
        $privateKeyDerEncoded = $this->cryptoService->privateKeyToDer($keyPair->getPrivateKey());

        $publicKeyHash = new Buffer(
            $this->cryptoService->computeHash($publicKeyDerEncoded, HashAlgorithms::SHA256)
        );
        $privateKeyHash = new Buffer(
            $this->cryptoService->computeHash($privateKeyDerEncoded, HashAlgorithms::SHA256)
        );

        $publicKeyReference = new PublicKeyReference($publicKeyHash->toHex());
        $privateKeyReference = new PrivateKeyReference($privateKeyHash->toHex());

        $publicKeyEntry = new KeyEntry($publicKeyHash->getData(), $publicKeyDerEncoded);
        $privateKeyEntry = new KeyEntry($publicKeyHash->getData(), $privateKeyDerEncoded);

        $this->persistKeyEntry($publicKeyReference, $publicKeyEntry);
        $this->persistKeyEntry($privateKeyReference, $privateKeyEntry);

        return new VirgilKeyPair($publicKeyReference, $privateKeyReference);
    }


    /**
     * @inheritdoc
     */
    public function encrypt($content, array $recipientsPublicKeys)
    {
        $cipher = $this->cryptoService->createCipher();
        $cipherInputOutput = $cipher->createInputOutput($content);

        $encryptedContent = $this->encryptContent($cipherInputOutput, $recipientsPublicKeys, $cipher);

        return new Buffer($encryptedContent);
    }


    /**
     * @inheritdoc
     */
    public function decrypt(BufferInterface $encryptedContent, PrivateKeyInterface $recipientPrivateKey)
    {
        $cipher = $this->cryptoService->createCipher();
        $cipherInputOutput = $cipher->createInputOutput($encryptedContent->getData());

        $decryptedContent = $this->decryptContent($cipherInputOutput, $recipientPrivateKey, $cipher);

        return new Buffer($decryptedContent);
    }


    /**
     * @inheritdoc
     */
    public function encryptStream($source, $sin, array $recipientsPublicKeys)
    {
        $cipher = $this->cryptoService->createStreamCipher();
        $cipherInputOutput = $cipher->createInputOutput($source, $sin);

        $this->encryptContent($cipherInputOutput, $recipientsPublicKeys, $cipher);
    }


    /**
     * @inheritdoc
     */
    public function decryptStream($source, $sin, PrivateKeyInterface $recipientPrivateKey)
    {
        $cipher = $this->cryptoService->createStreamCipher();
        $cipherInputOutput = $cipher->createInputOutput($source, $sin);

        $this->decryptContent($cipherInputOutput, $recipientPrivateKey, $cipher);
    }


    /**
     * @inheritdoc
     */
    public function signThenEncrypt($content, PrivateKeyInterface $signerPrivateKey, array $recipientsPublicKeys)
    {
        $cipher = $this->cryptoService->createCipher();
        $cipherInputOutput = $cipher->createInputOutput($content);

        $signature = $this->sign($content, $signerPrivateKey);
        $cipher->setCustomParam(self::CUSTOM_PARAM_KEY_SIGNATURE, $signature->getData());
        $encryptedContent = $this->encryptContent($cipherInputOutput, $recipientsPublicKeys, $cipher);

        return new Buffer($encryptedContent);
    }


    /**
     * @inheritdoc
     */
    public function decryptThenVerify(
        BufferInterface $encryptedAndSignedContent,
        PrivateKeyInterface $recipientPrivateKey,
        PublicKeyInterface $signerPublicKey
    ) {
        $cipher = $this->cryptoService->createCipher();
        $cipherInputOutput = $cipher->createInputOutput($encryptedAndSignedContent->getData());

        $decryptedContent = $this->decryptContent($cipherInputOutput, $recipientPrivateKey, $cipher);
        $signature = $cipher->getCustomParam(self::CUSTOM_PARAM_KEY_SIGNATURE);

        if (!$this->verify($decryptedContent, new Buffer($signature), $signerPublicKey)) {
            throw new SignatureIsNotValidException();
        }

        return new Buffer($decryptedContent);
    }


    /**
     * @inheritdoc
     */
    public function calculateFingerprint($content)
    {
        $contentHash = $this->cryptoService->computeHash($content, HashAlgorithms::SHA256);

        return new Buffer($contentHash);
    }


    /**
     * @inheritdoc
     */
    public function sign($content, PrivateKeyInterface $signerPrivateKey)
    {
        /** @var PrivateKeyReference $signerPrivateKey */
        $signerPrivateKeyEntry = $this->getKeyEntry($signerPrivateKey);

        $signerPrivateKeyEntryValue = $signerPrivateKeyEntry->getValue();

        $signature = $this->cryptoService->sign($content, $signerPrivateKeyEntryValue);

        return new Buffer($signature);
    }


    /**
     * @inheritdoc
     */
    public function verify($content, BufferInterface $signature, PublicKeyInterface $signerPublicKey)
    {
        /** @var PublicKeyReference $signerPublicKey */
        $signerPublicKeyEntry = $this->getKeyEntry($signerPublicKey);

        $signerPublicKeyEntryValue = $signerPublicKeyEntry->getValue();

        return $this->cryptoService->verify(
            $content,
            $signature->getData(),
            $signerPublicKeyEntryValue
        );
    }


    /**
     * @inheritdoc
     */
    public function signStream($source, PrivateKeyInterface $signerPrivateKey)
    {
        /** @var PrivateKeyReference $signerPrivateKey */
        $signerPrivateKeyEntry = $this->getKeyEntry($signerPrivateKey);

        $signerPrivateKeyEntryValue = $signerPrivateKeyEntry->getValue();

        $signature = $this->cryptoService->signStream($source, $signerPrivateKeyEntryValue);

        return new Buffer($signature);
    }


    /**
     * @inheritdoc
     */
    public function verifyStream($source, BufferInterface $signature, PublicKeyInterface $signerPublicKey)
    {
        /** @var PublicKeyReference $signerPublicKey */
        $signerPublicKeyEntry = $this->getKeyEntry($signerPublicKey);

        $signerPublicKeyEntryValue = $signerPublicKeyEntry->getValue();

        return $this->cryptoService->verifyStream($source, $signature->getData(), $signerPublicKeyEntryValue);
    }


    /**
     * @inheritdoc
     */
    public function extractPublicKey(PrivateKeyInterface $privateKey)
    {
        /** @var PrivateKeyReference $privateKey */
        $privateKeyEntry = $this->getKeyEntry($privateKey);

        $extractedPublicKey = $this->cryptoService->extractPublicKey($privateKeyEntry->getValue(), '');
        $extractedPublicKeyHash = new Buffer(
            $this->cryptoService->computeHash($extractedPublicKey, HashAlgorithms::SHA256)
        );

        $publicKeyReference = new PublicKeyReference($extractedPublicKeyHash->toHex());
        $extractedKeyEntry = new KeyEntry($privateKeyEntry->getRecipientId(), $extractedPublicKey);

        $this->persistKeyEntry($publicKeyReference, $extractedKeyEntry);

        return $publicKeyReference;
    }


    /**
     * @inheritdoc
     */
    public function exportPublicKey(PublicKeyInterface $publicKey)
    {
        /** @var PublicKeyReference $publicKey */
        $publicKeyEntry = $this->getKeyEntry($publicKey);

        $publicKeyEntryValue = $publicKeyEntry->getValue();

        $publicKeyDerEncoded = $this->cryptoService->publicKeyToDer($publicKeyEntryValue);

        return new Buffer($publicKeyDerEncoded);
    }


    /**
     * @inheritdoc
     */
    public function exportPrivateKey(PrivateKeyInterface $privateKey, $password = '')
    {
        /** @var PrivateKeyReference $privateKey */
        $privateKeyEntry = $this->getKeyEntry($privateKey);

        $privateKeyEntryValue = $privateKeyEntry->getValue();

        $privateKeyDerEncoded = $this->cryptoService->privateKeyToDer($privateKeyEntryValue, $password);

        return new Buffer($privateKeyDerEncoded);
    }


    /**
     * @inheritdoc
     */
    public function importPrivateKey(BufferInterface $exportedPrivateKey, $password = '')
    {
        $exportedPrivateKeyData = $exportedPrivateKey->getData();

        if (strlen($password) === 0) {
            $privateKeyDerEncoded = $this->cryptoService->privateKeyToDer($exportedPrivateKeyData);
        } else {
            $privateKeyDerEncoded = $this->cryptoService->decryptPrivateKey($exportedPrivateKeyData, $password);
        }

        $privateKeyHash = new Buffer(
            $this->cryptoService->computeHash($privateKeyDerEncoded, HashAlgorithms::SHA256)
        );

        $privateKeyReference = new PrivateKeyReference($privateKeyHash->toHex());

        $publicKeyHash = $this->cryptoService->computeHash(
            $this->cryptoService->extractPublicKey($privateKeyDerEncoded, ''),
            HashAlgorithms::SHA256
        );

        $privateKeyDerEncoded = $this->cryptoService->privateKeyToDer($privateKeyDerEncoded);

        $keyEntry = new KeyEntry($publicKeyHash, $privateKeyDerEncoded);

        $this->persistKeyEntry($privateKeyReference, $keyEntry);

        return $privateKeyReference;
    }


    /**
     * @inheritdoc
     */
    public function importPublicKey(BufferInterface $exportedPublicKey)
    {
        $publicKeyHash = new Buffer(
            $this->cryptoService->computeHash($exportedPublicKey->getData(), HashAlgorithms::SHA256)
        );

        $publicKeyReference = new PublicKeyReference($publicKeyHash->toHex());

        $publicKeyDerEncoded = $this->cryptoService->publicKeyToDer($exportedPublicKey->getData());

        $keyEntry = new KeyEntry($publicKeyHash->getData(), $publicKeyDerEncoded);

        $this->persistKeyEntry($publicKeyReference, $keyEntry);

        return $publicKeyReference;
    }


    /**
     * Encrypts the content with a list of recipients public keys and cipher.
     *
     * @param InputOutputInterface $cipherInputOutput
     * @param PublicKeyInterface[] $recipientsPublicKeys
     * @param CipherInterface      $cipher
     *
     * @return string
     */
    private function encryptContent(
        InputOutputInterface $cipherInputOutput,
        array $recipientsPublicKeys,
        CipherInterface $cipher
    ) {
        /** @var PublicKeyReference $recipientPublicKey */
        foreach ($recipientsPublicKeys as $recipientPublicKey) {
            $recipientPublicKeyEntry = $this->getKeyEntry($recipientPublicKey);

            $cipher->addKeyRecipient(
                $recipientPublicKeyEntry->getRecipientId(),
                $recipientPublicKeyEntry->getValue()
            );
        }

        return $cipher->encrypt($cipherInputOutput);
    }


    /**
     * Decrypts encrypted content by given recipient private key and cipher.
     *
     * @param InputOutputInterface $cipherInputOutput
     * @param PrivateKeyInterface  $recipientPrivateKey
     * @param CipherInterface      $cipher
     *
     * @return string
     */
    private function decryptContent(
        InputOutputInterface $cipherInputOutput,
        PrivateKeyInterface $recipientPrivateKey,
        CipherInterface $cipher
    ) {
        /** @var PrivateKeyReference $recipientPrivateKey */
        $recipientPrivateKeyEntry = $this->getKeyEntry($recipientPrivateKey);

        $decryptedContent = $cipher->decryptWithKey(
            $cipherInputOutput,
            $recipientPrivateKeyEntry->getRecipientId(),
            $recipientPrivateKeyEntry->getValue()
        );

        return $decryptedContent;
    }
}
