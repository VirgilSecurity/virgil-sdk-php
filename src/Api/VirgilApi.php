<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Cards\CardsManager;
use Virgil\Sdk\Api\Cards\CardsManagerInterface;

use Virgil\Sdk\Api\Keys\KeysManager;
use Virgil\Sdk\Api\Keys\KeysManagerInterface;

use Virgil\Sdk\Client\Card\Base64CardSerializer;
use Virgil\Sdk\Client\Card\PublishRequestCardMapper;

use Virgil\Sdk\Client\Requests\RequestSigner;

use Virgil\Sdk\Client\Validator\CardValidator;
use Virgil\Sdk\Client\Validator\CardVerifierInfoInterface;

use Virgil\Sdk\Client\VirgilClient;

/**
 * Virgil api is a one point to work with Virgil entities that provides high-level API such as cards and keys.
 */
class VirgilApi implements VirgilApiInterface
{
    /** @var KeysManagerInterface */
    public $Keys;

    /** @var CardsManagerInterface */
    public $Cards;

    /** @var VirgilApiContextInterface */
    private $virgilApiContext;


    /**
     * Class constructor.
     *
     * @param VirgilApiContextInterface $virgilApiContext
     */
    public function __construct(VirgilApiContextInterface $virgilApiContext)
    {
        $this->virgilApiContext = $virgilApiContext;

        $this->Keys = $this->initKeys($virgilApiContext);
        $this->Cards = $this->initCards($virgilApiContext);
    }


    /**
     * @inheritdoc
     */
    public function create($accessToken = null)
    {
        $virgilApiContext = new VirgilApiContext($accessToken);

        return new self($virgilApiContext);
    }


    /**
     * @inheritdoc
     */
    public function getKeys()
    {
        return $this->Keys;
    }


    /**
     * @inheritdoc
     */
    public function getCards()
    {
        return $this->Cards;
    }


    /**
     * @param VirgilApiContextInterface $virgilApiContext
     *
     * @return CardsManager
     */
    private function initCards(VirgilApiContextInterface $virgilApiContext)
    {
        $crypto = $virgilApiContext->getCrypto();
        $virgilClient = VirgilClient::create($virgilApiContext->getAccessToken());
        $requestSigner = new RequestSigner($crypto);
        $cardValidator = new CardValidator($crypto, $virgilApiContext->isUseBuiltInVerifiers());

        /** @var CardVerifierInfoInterface $cardVerifier */
        foreach ($virgilApiContext->getCardVerifiers() as $cardVerifier) {
            $verifierPublicKey = $crypto->importPublicKey($cardVerifier->getPublicKeyData());
            $cardValidator->addVerifier($cardVerifier->getCardId(), $verifierPublicKey);
        }

        $credentials = $virgilApiContext->getCredentials();
        $cardSerializer = Base64CardSerializer::create();
        $cardMapper = new PublishRequestCardMapper();


        return new CardsManager(
            $virgilClient, $requestSigner, $cardValidator, $crypto, $credentials, $cardSerializer, $cardMapper
        );
    }


    /**
     * @param VirgilApiContextInterface $virgilApiContext
     *
     * @return KeysManager
     */
    private function initKeys(VirgilApiContextInterface $virgilApiContext)
    {
        $crypto = $virgilApiContext->getCrypto();
        $keyStorage = $virgilApiContext->getKeyStorage();

        return new KeysManager($crypto, $keyStorage);
    }
}
