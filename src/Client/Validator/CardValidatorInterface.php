<?php
namespace Virgil\Sdk\Client\Validator;


use Virgil\Sdk\Client\Card;

/**
 * Interface designed for card validation.
 */
interface CardValidatorInterface
{
    /**
     * Validates the specified Card.
     *
     * @param Card $card
     *
     * @throws CardValidationException
     *
     * @return void
     */
    public function validate(Card $card);
}
