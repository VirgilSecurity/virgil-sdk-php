<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Cards\CardsManager;
use Virgil\Sdk\Api\Cards\CardsManagerInterface;

use Virgil\Sdk\Api\Keys\KeysManager;
use Virgil\Sdk\Api\Keys\KeysManagerInterface;

use Virgil\Sdk\Client\Card\Base64CardSerializer;
use Virgil\Sdk\Client\Card\CardMapperInterface;
use Virgil\Sdk\Client\Card\CardSerializerInterface;
use Virgil\Sdk\Client\Card\PublishRequestCardMapper;

use Virgil\Sdk\Client\Requests\RequestSigner;
use Virgil\Sdk\Client\Requests\RequestSignerInterface;

use Virgil\Sdk\Client\Validator\CardValidator;
use Virgil\Sdk\Client\Validator\CardValidatorInterface;
use Virgil\Sdk\Client\Validator\CardVerifierInfoInterface;

use Virgil\Sdk\Client\VirgilClient;
use Virgil\Sdk\Client\VirgilClientInterface;

/**
 * Virgil api is a one point to work with Virgil entities that provides high-level API such as cards and keys.
 *
 * @property CardsManager Cards
 * @property KeysManager  Keys
 */
class VirgilApi implements VirgilApiInterface
{
    /** @var KeysManagerInterface */
    private $keysManager;

    /** @var CardsManagerInterface */
    private $cardsManager;

    /** @var VirgilApiContextInterface */
    private $virgilApiContext;

    /** @var VirgilClientInterface */
    private $virgilClient;

    /** @var RequestSignerInterface */
    private $requestSigner;

    /** @var CardValidatorInterface */
    private $cardValidator;

    /** @var CardSerializerInterface */
    private $cardSerializer;

    /** @var CardMapperInterface */
    private $cardsMapper;


    /**
     * Class constructor.
     *
     * @param VirgilApiContextInterface $virgilApiContext
     */
    public function __construct(VirgilApiContextInterface $virgilApiContext)
    {
        $this->virgilApiContext = $virgilApiContext;
    }


    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        $methodName = 'get' . $name;

        return call_user_func([$this, $methodName]);
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
        if ($this->keysManager === null) {
            $this->keysManager = $this->initKeysManager($this->virgilApiContext);
        }

        return $this->keysManager;
    }


    /**
     * @inheritdoc
     */
    public function getCards()
    {
        if ($this->cardsManager === null) {
            $this->cardsManager = $this->initCardsManager($this->virgilApiContext);
        }

        return $this->cardsManager;
    }


    /**
     * @inheritdoc
     */
    public function setClient(VirgilClientInterface $virgilClient)
    {
        $this->virgilClient = $virgilClient;

        $this->resetCardsManager();

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getClient()
    {
        if ($this->virgilClient === null) {
            $this->virgilClient = VirgilClient::create($this->virgilApiContext->getAccessToken());
        }

        return $this->virgilClient;
    }


    /**
     * @inheritdoc
     */
    public function setRequestSigner(RequestSignerInterface $requestSigner)
    {
        $this->requestSigner = $requestSigner;

        $this->resetCardsManager();

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getRequestSigner()
    {
        if ($this->requestSigner === null) {
            $this->requestSigner = new RequestSigner($this->virgilApiContext->getCrypto());
        }

        return $this->requestSigner;
    }


    /**
     * @inheritdoc
     */
    public function setCardValidator(CardValidatorInterface $cardValidator)
    {
        $this->cardValidator = $cardValidator;

        $this->resetCardsManager();

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getCardValidator()
    {
        if ($this->cardValidator === null) {
            $this->cardValidator = new CardValidator(
                $this->virgilApiContext->getCrypto(), $this->virgilApiContext->isUseBuiltInVerifiers()
            );
        }

        return $this->cardValidator;
    }


    /**
     * @inheritdoc
     */
    public function setCardSerializer(CardSerializerInterface $cardSerializer)
    {
        $this->cardSerializer = $cardSerializer;

        $this->resetCardsManager();

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function getCardSerializer()
    {
        if ($this->cardSerializer === null) {
            $this->cardSerializer = Base64CardSerializer::create();
        }

        return $this->cardSerializer;
    }


    /**
     * @inheritdoc
     */
    public function getCardsMapper()
    {
        if ($this->cardsMapper === null) {
            $this->cardsMapper = new PublishRequestCardMapper();
        }

        return $this->cardsMapper;
    }


    /**
     * @inheritdoc
     */
    public function setCardsMapper(CardMapperInterface $cardsMapper)
    {
        $this->cardsMapper = $cardsMapper;

        $this->resetCardsManager();

        return $this;
    }


    /**
     * @param VirgilApiContextInterface $virgilApiContext
     *
     * @return CardsManager
     */
    private function initCardsManager(VirgilApiContextInterface $virgilApiContext)
    {
        $crypto = $virgilApiContext->getCrypto();

        $virgilClient = $this->getClient();
        $cardValidator = $this->getCardValidator();
        $requestSigner = $this->getRequestSigner();
        $credentials = $virgilApiContext->getCredentials();
        $cardSerializer = $this->getCardSerializer();
        $cardsMapper = $this->getCardsMapper();

        /** @var CardVerifierInfoInterface $cardVerifier */
        foreach ($virgilApiContext->getCardVerifiers() as $cardVerifier) {
            $verifierPublicKey = $crypto->importPublicKey($cardVerifier->getPublicKeyData());
            $cardValidator->addVerifier($cardVerifier->getCardId(), $verifierPublicKey);
        }

        $virgilClient->setCardValidator($cardValidator);

        return new CardsManager(
            $virgilClient, $requestSigner, $cardValidator, $crypto, $credentials, $cardSerializer, $cardsMapper
        );
    }


    /**
     * @param VirgilApiContextInterface $virgilApiContext
     *
     * @return KeysManager
     */
    private function initKeysManager(VirgilApiContextInterface $virgilApiContext)
    {
        $crypto = $virgilApiContext->getCrypto();
        $keyStorage = $virgilApiContext->getKeyStorage();

        return new KeysManager($crypto, $keyStorage);
    }


    /**
     * Clears cards manager.
     */
    private function resetCardsManager()
    {
        $this->cardsManager = null;
    }
}
