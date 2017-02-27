<?php
namespace Virgil\Sdk\Tests\Unit\Api;


use Virgil\Sdk\Api\Cards\VirgilCard;

use Virgil\Sdk\Api\VirgilApiContextInterface;

use Virgil\Sdk\Client\Card;

class Cards
{
    public static function createVirgilCard(VirgilApiContextInterface $virgilApiContext, Card $card)
    {
        return new VirgilCard($virgilApiContext, $card);
    }

}
