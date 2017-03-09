<?php
namespace Virgil\Sdk\Client\Validator;


use Virgil\Sdk\Client\Card;

/**
 * Class is defined for ignoring card validation by default.
 */
class StubCardValidator implements CardValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function validate(Card $card)
    {
        return $this;
    }
}
