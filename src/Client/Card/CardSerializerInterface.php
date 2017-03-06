<?php
namespace Virgil\Sdk\Client\Card;


use Virgil\Sdk\Client\Card;

/**
 * Interface provides methods for Card serialization and unserialization.
 */
interface CardSerializerInterface
{
    /**
     * Unserializes serialized string to card.
     *
     * @param string $serialized serialized string.
     *
     * @return Card
     */
    public function unserialize($serialized);


    /**
     * Serializes card to serialization string.
     *
     * @param Card $card
     *
     * @return string serialized string.
     */
    public function serialize(Card $card);
}
