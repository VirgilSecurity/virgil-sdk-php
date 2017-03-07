<?php
namespace Virgil\Sdk\Api\Cards;


use Virgil\Sdk\Api\Cards\Identity\IdentityValidationToken;

use Virgil\Sdk\Api\CredentialsInterface;

use Virgil\Sdk\Api\Keys\VirgilKey;

use Virgil\Sdk\Api\VirgilApiContextInterface;

use Virgil\Sdk\Client\Card;

use Virgil\Sdk\Client\Card\Base64CardSerializer;
use Virgil\Sdk\Client\Card\CardMapperInterface;
use Virgil\Sdk\Client\Card\CardSerializerInterface;
use Virgil\Sdk\Client\Card\PublishRequestCardMapper;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;
use Virgil\Sdk\Client\Requests\Constants\RevocationReasons;

use Virgil\Sdk\Client\Requests\PublishCardRequest;
use Virgil\Sdk\Client\Requests\PublishGlobalCardRequest;
use Virgil\Sdk\Client\Requests\RequestSignerInterface;
use Virgil\Sdk\Client\Requests\RevokeCardRequest;
use Virgil\Sdk\Client\Requests\RevokeGlobalCardRequest;
use Virgil\Sdk\Client\Requests\SearchCardRequest;

use Virgil\Sdk\Client\VirgilClientInterface;

use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;

use Virgil\Sdk\Contracts\CryptoInterface;

/**
 * Class manages virgil cards.
 */
class CardsManager implements CardsManagerInterface
{
    /** @var VirgilApiContextInterface */
    private $virgilApiContext;

    /** @var CardSerializerInterface */
    private $cardSerializer;

    /** @var VirgilClientInterface */
    private $virgilClient;

    /** @var PublishRequestCardMapper */
    private $cardMapper;

    /** @var RequestSignerInterface */
    private $requestSigner;

    /** @var CredentialsInterface */
    private $credentials;

    /** @var CryptoInterface */
    private $virgilCrypto;


    /**
     * Class constructor.
     *
     * @param VirgilApiContextInterface $virgilApiContext
     */
    public function __construct(VirgilApiContextInterface $virgilApiContext)
    {
        $this->virgilApiContext = $virgilApiContext;
        $this->virgilClient = $virgilApiContext->getClient();
        $this->requestSigner = $virgilApiContext->getRequestSigner();
        $this->credentials = $virgilApiContext->getCredentials();
        $this->virgilCrypto = $virgilApiContext->getCrypto();
        $this->cardSerializer = Base64CardSerializer::create();
        $this->cardMapper = new PublishRequestCardMapper();
    }


    /**
     * @inheritdoc
     */
    public function import($exportedVirgilCard)
    {
        $card = $this->cardSerializer->unserialize($exportedVirgilCard);

        return $this->cardToVirgilCard($card);
    }


    /**
     * @inheritdoc
     */
    public function publishGlobal(VirgilCard $virgilCard, IdentityValidationToken $identityValidationToken)
    {
        $card = $virgilCard->getCard();

        $signedRequestModel = $this->cardMapper->toModel($card, $identityValidationToken->getToken());

        /** @var PublishGlobalCardRequest $publishGlobalCardRequest */
        $publishGlobalCardRequest = PublishGlobalCardRequest::import($signedRequestModel);

        $publishedCard = $this->virgilClient->publishGlobalCard($publishGlobalCardRequest);

        $virgilCard->setCard($publishedCard);

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function publish(VirgilCard $virgilCard)
    {
        $card = $virgilCard->getCard();

        $signedRequestModel = $this->cardMapper->toModel($card);

        $appId = $this->credentials->getAppId();
        $appKey = $this->credentials->getAppKey($this->virgilCrypto);

        /** @var PublishCardRequest $publishCardRequest */
        $publishCardRequest = PublishCardRequest::import($signedRequestModel);

        $this->requestSigner->authoritySign($publishCardRequest, $appId, $appKey);

        $publishedCard = $this->virgilClient->createCard($publishCardRequest);

        $virgilCard->setCard($publishedCard);

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function revoke(VirgilCard $virgilCard)
    {
        $cardId = $virgilCard->getCard()
                             ->getId()
        ;

        $appId = $this->credentials->getAppId();
        $appKey = $this->credentials->getAppKey($this->virgilCrypto);

        $revokeCardRequest = new RevokeCardRequest($cardId, RevocationReasons::TYPE_UNSPECIFIED);

        $this->requestSigner->authoritySign($revokeCardRequest, $appId, $appKey);

        $this->virgilClient->revokeCard($revokeCardRequest);

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function revokeGlobal(
        VirgilCard $virgilCard,
        VirgilKey $virgilKey,
        IdentityValidationToken $identityValidationToken
    ) {
        $cardId = $virgilCard->getCard()
                             ->getId()
        ;

        $validationToken = $identityValidationToken->getToken();

        $revokeGlobalCardRequest = new RevokeGlobalCardRequest(
            $cardId, RevocationReasons::TYPE_UNSPECIFIED, new ValidationModel($validationToken)
        );

        $this->requestSigner->authoritySign($revokeGlobalCardRequest, $cardId, $virgilKey->getPrivateKey());

        $this->virgilClient->revokeGlobalCard($revokeGlobalCardRequest);

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setCardSerializer(CardSerializerInterface $cardSerializer)
    {
        $this->cardSerializer = $cardSerializer;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function setCardMapper(CardMapperInterface $cardMapper)
    {
        $this->cardMapper = $cardMapper;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function get($cardId)
    {
        $card = $this->virgilClient->getCard($cardId);

        return $this->cardToVirgilCard($card);
    }


    /**
     * @inheritdoc
     */
    public function find(array $identities, $identityType = null)
    {
        $searchCardRequest = new SearchCardRequest($identityType, CardScopes::TYPE_APPLICATION);

        $searchCardRequest->setIdentities($identities);

        $cards = $this->virgilClient->searchCards($searchCardRequest);

        $virgilCards = array_map([$this, 'cardToVirgilCard'], $cards);

        return new VirgilCards($this->virgilApiContext, $virgilCards);
    }


    /**
     * @inheritdoc
     */
    public function findGlobal(array $identities, $identityType = null)
    {
        $searchCardRequest = new SearchCardRequest($identityType, CardScopes::TYPE_GLOBAL);

        $searchCardRequest->setIdentities($identities);

        $cards = $this->virgilClient->searchCards($searchCardRequest);

        $virgilCards = array_map([$this, 'cardToVirgilCard'], $cards);

        return new VirgilCards($this->virgilApiContext, $virgilCards);
    }


    private function cardToVirgilCard(Card $card)
    {
        return new VirgilCard($this->virgilApiContext, $card);
    }
}
