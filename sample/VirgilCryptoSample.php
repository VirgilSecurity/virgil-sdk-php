<?php

require '../vendor/autoload.php';

use Virgil\CryptoImpl\VirgilAccessTokenSigner;
use Virgil\CryptoImpl\VirgilCardCrypto;
use Virgil\CryptoImpl\VirgilCrypto;
use Virgil\CryptoImpl\VirgilPrivateKeyExporter;
use Virgil\Sdk\Card;
use Virgil\Sdk\CardManager;
use Virgil\Sdk\CardParams;
use Virgil\Sdk\Storage\PrivateKeyStorage;
use Virgil\Sdk\Verification\VirgilCardVerifier;
use Virgil\Sdk\Web\Authorization\CallbackJwtProvider;
use Virgil\Sdk\Web\Authorization\JwtGenerator;
use Virgil\Sdk\Web\Authorization\TokenContext;

/**
 * Class VirgilCryptoSample
 */
class VirgilCryptoSample
{
    /**
     * @var array
     */
    private $auth;

    /**
     * @var
     */
    private $identity;

    /**
     * @var \Virgil\CryptoImpl\VirgilKeyPair
     */
    private $keyPair;

    /**
     * @var PrivateKeyStorage
     */
    private $privateKeyStorage;

    /**
     * @var string
     */
    private $storagePath = "./keys/";

    /**
     * VirgilCryptoService constructor.
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     * @throws \Virgil\Sdk\VirgilException
     */
    public function __construct()
    {
        $this->auth = [
            'apiKey' => $_ENV["SAMPLE_API_KEY"],
            'apiKeyId' => $_ENV["SAMPLE_API_KEY_ID"],
            'appId' => $_ENV["SAMPLE_APP_ID"],
            'ttl' => $_ENV["SAMPLE_JWT_TTL"]
        ];

        $this->keyPair = $this->generateKeys();

        $exporter = new VirgilPrivateKeyExporter($_ENV["SAMPLE_PRIVATE_KEY_PASSWORD"]);
        $this->privateKeyStorage = new PrivateKeyStorage($exporter, $this->storagePath);
    }

    // PUBLIC FUNCTIONS:

    /**
     * @param $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return Card
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     * @throws \Virgil\Sdk\CardClientException
     * @throws \Virgil\Sdk\CardVerificationException
     * @throws \Virgil\Sdk\VirgilException
     */
    public function storePrivateKeyAndCreateCard()
    {
        $this->storePrivateKey($this->identity);
        return $this->createCard($this->identity);
    }

    /**
     * @param $recipientIdentity
     * @param $dataToEncrypt
     * @return string
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     * @throws \Virgil\Sdk\CardClientException
     * @throws \Virgil\Sdk\CardVerificationException
     * @throws \Virgil\Sdk\VirgilException
     */
    public function signThenEncryptData($recipientIdentity, $dataToEncrypt)
    {
        $cards = $this->getUserCardsByIdentity($recipientIdentity);

        $recipientCardsPublicKeys = array_map(
            function (Card $card) {
                return $card->getPublicKey();
            },
            $cards
        );

        $encryptedData = $this->getCrypto()->signThenEncrypt(
            $dataToEncrypt,
            $this->loadPrivateKey($this->identity)->getPrivateKey(),
            $recipientCardsPublicKeys
        );

        return $encryptedData;
    }

    /**
     * @param $senderIdentity
     * @param $dataToDecrypt
     * @return string
     * @throws \Virgil\CryptoImpl\SignatureIsNotValidException
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     * @throws \Virgil\Sdk\CardClientException
     * @throws \Virgil\Sdk\CardVerificationException
     * @throws \Virgil\Sdk\VirgilException
     */
    public function decryptDataThenVerifySignature($senderIdentity, $dataToDecrypt)
    {
        $cards = $this->getUserCardsByIdentity($senderIdentity);

        $senderCardsPublicKeys = array_map(
            function (Card $card) {
                return $card->getPublicKey();
            },
            $cards
        );

        $decryptedData = $this->getCrypto()->decryptThenVerify(
            $dataToDecrypt,
            $this->loadPrivateKey($this->identity)->getPrivateKey(),
            $senderCardsPublicKeys
        );

        return $decryptedData;
    }

    /**
     * @param $identity
     * @throws \Virgil\Sdk\VirgilException
     */
    public function storePrivateKey($identity)
    {
        $this->privateKeyStorage->store($this->keyPair->getPrivateKey(), $identity);
    }

    /**
     * @param $identity
     * @return \Virgil\Sdk\Storage\PrivateKeyEntry
     * @throws \Virgil\Sdk\VirgilException
     */
    public function loadPrivateKey($identity)
    {
        return $this->privateKeyStorage->load($identity);
    }

    /**
     * @param $identity
     * @throws \Virgil\Sdk\VirgilException
     */
    public function deletePrivateKey($identity)
    {
        $this->privateKeyStorage->delete($identity);
    }

    /**
     * @param $identity
     * @return Card[]
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     * @throws \Virgil\Sdk\CardClientException
     * @throws \Virgil\Sdk\CardVerificationException
     */
    public function getUserCardsByIdentity($identity)
    {
        return $this->getCardManager()->searchCards($identity);
    }

    /**
     * @param $id
     * @return Card
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     * @throws \Virgil\Sdk\CardClientException
     * @throws \Virgil\Sdk\CardVerificationException
     */
    public function getUserCardById($id)
    {
        return $this->getCardManager()->getCard($id);
    }

    // PRIVATE FUNCTIONS:

    /**
     * @return VirgilCrypto
     */
    private function getCrypto()
    {
        return new VirgilCrypto();
    }

    /**
     * @return VirgilCardCrypto
     */
    private function getCardCrypto()
    {
        return new VirgilCardCrypto();
    }

    /**
     * @return VirgilCardVerifier
     */
    private function getCardVerifier()
    {
        return new VirgilCardVerifier($this->getCardCrypto());
    }

    /**
     * @return CardManager
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     */
    private function getCardManager()
    {
        return new CardManager($this->getCardCrypto(), $this->setUpJWTProvider(), $this->getCardVerifier());
    }

    /**
     * @return \Virgil\CryptoImpl\VirgilKeyPair
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     */
    private function generateKeys()
    {
        return $this->getCrypto()->generateKeys();
    }

    /**
     * @return string
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     */
    private function getGeneratedJWT()
    {
        $privateKeyStr = $this->auth['apiKey'];
        $apiKeyData = base64_decode($privateKeyStr);

        $privateKey = $this->getCrypto()->importPrivateKey($apiKeyData);

        $accessTokenSigner = new VirgilAccessTokenSigner();

        $jwtGenerator = new JwtGenerator($privateKey, $this->auth['apiKeyId'], $accessTokenSigner, $this->auth['appId'],
            $this->auth['ttl']);

        $token = $jwtGenerator->generateToken($this->identity);

        $jwt = $token->__toString();

        return $jwt;
    }

    /**
     * @return CallbackJwtProvider
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     */
    private function setUpJWTProvider()
    {
        $jwt = $this->getGeneratedJWT();

        $authenticatedQueryToServerSide = function (TokenContext $context) use ($jwt) {
            return $jwt;
        };

        return new CallbackJwtProvider($authenticatedQueryToServerSide);
    }

    /**
     * @return Card
     * @throws \Virgil\CryptoImpl\VirgilCryptoException
     * @throws \Virgil\Sdk\CardClientException
     * @throws \Virgil\Sdk\CardVerificationException
     */
    private function createCard()
    {
        return $this->getCardManager()->publishCard(
            CardParams::create(
                [
                    CardParams::PublicKey => $this->keyPair->getPublicKey(),
                    CardParams::PrivateKey => $this->keyPair->getPrivateKey(),
                ]
            )
        );
    }
}