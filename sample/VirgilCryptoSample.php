<?php

declare(strict_types=1);

require '../vendor/autoload.php';

use Virgil\Crypto\Core\VirgilKeys\VirgilKeyPair;
use Virgil\Crypto\VirgilCrypto;
use Virgil\Sdk\Card;
use Virgil\Sdk\CardManager;
use Virgil\Sdk\CardParams;
use Virgil\Sdk\Http\VirgilAgent\HttpVirgilAgent;
use Virgil\Sdk\Storage\PrivateKeyStorage;
use Virgil\Sdk\Verification\VirgilCardVerifier;
use Virgil\Sdk\Web\Authorization\CallbackJwtProvider;
use Virgil\Sdk\Web\Authorization\JwtGenerator;
use \Virgil\Crypto\Core\VirgilKeys\VirgilPublicKeyCollection;
use Virgil\Sdk\Web\CardClient;
use \Virgil\Sdk\Storage\PrivateKeyEntry;

/**
 * Class VirgilCryptoSample
 */
class VirgilCryptoSample
{
    private $auth;

    private $identity;

    private $keyPair;

    private $privateKeyStorage;

    private $storagePath = "./keys/";

    public function __construct()
    {
        $this->auth = [
            'serviceAddress' => $_ENV["SERVICE_ADDRESS"],
            'serviceKey' => $_ENV["SERVICE_KEY"],
            'apiKey' => $_ENV["SAMPLE_API_KEY"],
            'apiKeyId' => $_ENV["SAMPLE_API_KEY_ID"],
            'appId' => $_ENV["SAMPLE_APP_ID"],
            'ttl' => (int)$_ENV["SAMPLE_JWT_TTL"]
        ];

        $this->keyPair = $this->generateKeys();

        $this->privateKeyStorage = new PrivateKeyStorage($this->getVirgilCrypto(), $this->storagePath);
    }

    // PUBLIC FUNCTIONS:

    public function setIdentity(string $identity): void
    {
        $this->identity = $identity;
    }

    public function storePrivateKeyAndCreateCard(): Card
    {
        $this->storePrivateKey($this->identity);
        return $this->createCard();
    }

    public function signThenEncryptData(string $recipientIdentity, string $dataToEncrypt): ?string
    {
        $cards = $this->getUserCardsByIdentity($recipientIdentity);

        $keyCollection = new VirgilPublicKeyCollection();
        foreach ($cards as $card) {
            $keyCollection->addPublicKey($card->getPublicKey());
        }

        $encryptedData = $this->getVirgilCrypto()->signAndEncrypt(
            $dataToEncrypt,
            $this->loadPrivateKey($this->identity)->getPrivateKey(),
            $keyCollection
        );

        return $encryptedData;
    }

    public function decryptDataThenVerifySignature(string $senderIdentity, string $dataToDecrypt): ?string
    {
        $senderCards = $this->getUserCardsByIdentity($senderIdentity);
        $keyCollection = new VirgilPublicKeyCollection();

        foreach ($senderCards as $card) {
            $keyCollection->addPublicKey($card->getPublicKey());
        }

        $decryptedData = $this->getVirgilCrypto()->decryptAndVerify(
            $dataToDecrypt,
            $this->loadPrivateKey($this->identity)->getPrivateKey(),
            $keyCollection
        );

        return $decryptedData;
    }

    /**
     * @throws \Virgil\Sdk\Exceptions\VirgilException
     */
    public function storePrivateKey(string $identity): void
    {
        $this->privateKeyStorage->store($this->keyPair->getPrivateKey(), $identity);
    }

    /**
     * @throws \Virgil\Sdk\Exceptions\VirgilException
     */
    public function loadPrivateKey(string $identity): PrivateKeyEntry
    {
        return $this->privateKeyStorage->load($identity);
    }

    /**
     * @throws \Virgil\Sdk\Exceptions\VirgilException
     */
    public function deletePrivateKey(string $identity): void
    {
        $this->privateKeyStorage->delete($identity);
    }

    /**
     * @throws \Virgil\Sdk\Exceptions\CardClientException
     * @throws \Virgil\Sdk\Exceptions\CardVerificationException
     */
    public function getUserCardsByIdentity(string $identity): array
    {
        return $this->getCardManager()->searchCards($identity);
    }

    /**
     * @throws \Virgil\Sdk\Exceptions\CardClientException
     * @throws \Virgil\Sdk\Exceptions\CardVerificationException
     */
    public function getUserCardById(string $id): Card
    {
        return $this->getCardManager()->getCard($id);
    }

    // PRIVATE FUNCTIONS:

    private function getVirgilCrypto(): VirgilCrypto
    {
        return new VirgilCrypto();
    }

    private function getCardVerifier(): VirgilCardVerifier
    {
        return new VirgilCardVerifier(
            $this->getVirgilCrypto(),
            true,
            true,
            [],
            $this->auth['serviceKey']
        );
    }

    /**
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     */
    private function getCardManager(): CardManager
    {
        return new CardManager(
            $this->getVirgilCrypto(),
            $this->setUpJWTProvider(),
            $this->getCardVerifier(),
            new CardClient(new HttpVirgilAgent(), $this->auth['serviceAddress'])
        );
    }

    private function generateKeys(): VirgilKeyPair
    {
        return $this->getVirgilCrypto()->generateKeyPair();
    }

    /**
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     */
    private function getGeneratedJWT(): string
    {
        $privateKeyStr = $this->auth['apiKey'];
        $apiKeyData = base64_decode($privateKeyStr);

        $privateKey = $this->getVirgilCrypto()->importPrivateKey($apiKeyData);

        $jwtGenerator = new JwtGenerator($privateKey->getPrivateKey(), $this->auth['apiKeyId'],
            $this->getVirgilCrypto(), $this->auth['appId'],
            $this->auth['ttl']);

        $token = $jwtGenerator->generateToken($this->identity);

        $jwt = $token->__toString();

        return $jwt;
    }

    /**
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     */
    private function setUpJWTProvider(): CallbackJwtProvider
    {
        $jwt = $this->getGeneratedJWT();

        $authenticatedQueryToServerSide = function () use ($jwt) {
            return $jwt;
        };

        return new CallbackJwtProvider($authenticatedQueryToServerSide);
    }

    /**
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     * @throws \Virgil\Sdk\Exceptions\CardClientException
     * @throws \Virgil\Sdk\Exceptions\CardVerificationException
     */
    private function createCard(): Card
    {
        return $this->getCardManager()->publishCard(
            CardParams::create(
                [
                    CardParams::Identity => $this->identity,
                    CardParams::PublicKey => $this->keyPair->getPublicKey(),
                    CardParams::PrivateKey => $this->keyPair->getPrivateKey(),
                ]
            )
        );
    }
}
