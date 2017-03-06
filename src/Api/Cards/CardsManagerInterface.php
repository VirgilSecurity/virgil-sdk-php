<?php
namespace Virgil\Sdk\Api\Cards;


use Virgil\Sdk\Client\Card\CardSerializerInterface;

/**
 * Interface provides methods to work with virigl card.
 */
interface CardsManagerInterface
{
    /**
     * Imports a virgil card from string representation.
     *
     * @param string $exportedVirgilCard
     *
     * @return VirgilCardInterface
     */
    public function import($exportedVirgilCard);


    /**
     * Sets custom card serializer.
     *
     * @param CardSerializerInterface $cardSerializer
     *
     * @return $this
     */
    public function setCardSerializer(CardSerializerInterface $cardSerializer);
}
