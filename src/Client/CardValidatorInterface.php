<?php
namespace Virgil\Sdk\Client;


interface CardValidatorInterface
{
    /**
     * Validates the specified Card.
     *
     * @param Card $card
     * @return bool
     */
    public function validate(Card $card);
}
