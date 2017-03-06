<?php
namespace Virgil\Sdk\Api\Cards;


use Virgil\Sdk\Api\Cards\Identity\IdentityValidationToken;

use Virgil\Sdk\Api\VirgilApiContextInterface;

use Virgil\Sdk\Client\Card\Base64CardSerializer;
use Virgil\Sdk\Client\Card\CardMapperInterface;
use Virgil\Sdk\Client\Card\CardSerializerInterface;
use Virgil\Sdk\Client\Card\PublishRequestCardMapper;

use Virgil\Sdk\Client\Requests\PublishCardRequest;
use Virgil\Sdk\Client\Requests\PublishGlobalCardRequest;

use Virgil\Sdk\Client\VirgilClientInterface;

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


    /**
     * Class constructor.
     *
     * @param VirgilApiContextInterface $virgilApiContext
     */
    public function __construct(VirgilApiContextInterface $virgilApiContext)
    {
        $this->virgilApiContext = $virgilApiContext;

        $this->virgilClient = $virgilApiContext->getClient();

        $this->cardSerializer = Base64CardSerializer::create();

        $this->cardMapper = new PublishRequestCardMapper();
    }


    /**
     * @inheritdoc
     */
    public function import($exportedVirgilCard)
    {
        $card = $this->cardSerializer->unserialize($exportedVirgilCard);

        return new VirgilCard($this->virgilApiContext, $card);
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

        /** @var PublishCardRequest $publishCardRequest */
        $publishCardRequest = PublishCardRequest::import($signedRequestModel);

        $publishedCard = $this->virgilClient->createCard($publishCardRequest);

        $virgilCard->setCard($publishedCard);

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
}
