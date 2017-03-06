<?php
namespace Virgil\Sdk\Api\Cards;


use Virgil\Sdk\Api\VirgilApiContextInterface;

use Virgil\Sdk\Client\Card;

use Virgil\Sdk\Client\Card\Base64CardSerializer;
use Virgil\Sdk\Client\Card\CardSerializerInterface;

use Virgil\Sdk\Client\VirgilClientInterface;

use Virgil\Sdk\Contracts\BufferInterface;
use Virgil\Sdk\Contracts\CryptoInterface;

use Virgil\Sdk\Api\Cards\Identity\IdentityVerificationAttempt;
use Virgil\Sdk\Api\Cards\Identity\IdentityVerificationOptions;
use Virgil\Sdk\Api\Cards\Identity\IdentityVerificationOptionsInterface;

/**
 * A Virgil Card is the main entity of the Virgil Security services, it includes an information about the user and his
 * public key. The Virgil Card identifies the user by one of his available types, such as an email, a phone number,
 * etc.
 */
class VirgilCard implements VirgilCardInterface
{
    /** @var Card */
    private $card;

    /** @var CryptoInterface */
    private $virgilCrypto;

    /** @var VirgilClientInterface */
    private $virgilClient;

    /** @var CardSerializerInterface */
    private $cardSerializer;


    /**
     * Class constructor.
     *
     * @param VirgilApiContextInterface $virgilApiContext
     * @param Card                      $card
     */
    public function __construct(VirgilApiContextInterface $virgilApiContext, Card $card)
    {
        $this->virgilCrypto = $virgilApiContext->getCrypto();

        $this->virgilClient = $virgilApiContext->getClient();

        $this->cardSerializer = Base64CardSerializer::create();

        $this->card = $card;
    }


    /**
     * @inheritdoc
     */
    public function getPublicKey()
    {
        $publicKeyData = $this->card->getPublicKeyData();

        return $this->virgilCrypto->importPublicKey($publicKeyData);
    }


    /**
     * @inheritdoc
     */
    public function checkIdentity(IdentityVerificationOptionsInterface $identityVerificationOptions = null)
    {
        if ($identityVerificationOptions === null) {
            $identityVerificationOptions = new IdentityVerificationOptions();
        }

        $actionId = $this->virgilClient->verifyIdentity(
            $this->card->getIdentity(),
            $this->card->getIdentityType(),
            $identityVerificationOptions->getExtraFields()
        );

        return new IdentityVerificationAttempt(
            $this->virgilClient,
            $actionId,
            $identityVerificationOptions->getTimeToLive(),
            $identityVerificationOptions->getCountToLive(),
            $this->card->getIdentityType(),
            $this->card->getIdentity()
        );
    }


    /**
     * @inheritdoc
     */
    public function encrypt($content)
    {
        return $this->virgilCrypto->encrypt((string)$content, [$this->getPublicKey()]);
    }


    /**
     * @inheritdoc
     */
    public function verify($content, BufferInterface $signature)
    {
        return $this->virgilCrypto->verify((string)$content, $signature, $this->getPublicKey());
    }


    /**
     * @inheritdoc
     */
    public function export()
    {
        return $this->cardSerializer->serialize($this->card);
    }


    /**
     * @inheritdoc
     */
    public function setCardSerializer(CardSerializerInterface $cardSerializer)
    {
        $this->cardSerializer = $cardSerializer;

        return $this;
    }
}
