<?php
namespace Virgil\Sdk\Tests\Unit;


use Virgil\Sdk\Client\Card as VirgilClientCard;

class Card
{
    public static function createCard($cardArgs)
    {
        return new VirgilClientCard(...$cardArgs);
    }


    public static function createCards($cardsArgs)
    {
        $cardArgsToCard = function ($cardArgs) {
            return self::createCard($cardArgs);
        };

        return array_map(
            $cardArgsToCard,
            $cardsArgs
        );
    }

}
