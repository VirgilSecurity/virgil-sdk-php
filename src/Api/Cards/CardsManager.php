<?php
namespace Virgil\Sdk\Api\Cards;


use Virgil\Sdk\Api\VirgilApiContextInterface;

use Virgil\Sdk\Client\Card\Base64CardSerializer;
use Virgil\Sdk\Client\Card\CardSerializerInterface;

/**
 * Class manages virgil cards.
 */
class CardsManager implements CardsManagerInterface
{
    /** @var VirgilApiContextInterface */
    private $virgilApiContext;

    /** @var CardSerializerInterface */
    private $cardSerializer;


    /**
     * Class constructor.
     *
     * @param VirgilApiContextInterface $virgilApiContext
     */
    public function __construct(VirgilApiContextInterface $virgilApiContext)
    {
        $this->virgilApiContext = $virgilApiContext;

        $this->cardSerializer = Base64CardSerializer::create();
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
    public function setCardSerializer(CardSerializerInterface $cardSerializer)
    {
        $this->cardSerializer = $cardSerializer;

        return $this;
    }
}
