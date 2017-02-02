<?php
namespace Virgil\Sdk\Tests\Unit\Client\Requests;


use Virgil\Sdk\Buffer;
use Virgil\Sdk\Client\Requests\CreateCardRequest;
use Virgil\Sdk\Client\Requests\RevokeCardRequest;
use Virgil\Sdk\Client\Requests\SearchCardRequest;

class CardRequest
{

    public static function createCreateCardRequest($createCardRequestArgs, $signs = [])
    {
        $createCardRequest = new CreateCardRequest(...$createCardRequestArgs);

        foreach ($signs as $signKey => $sign) {
            $createCardRequest->appendSignature($signKey, Buffer::fromBase64($sign));
        }

        return $createCardRequest;
    }


    public static function createRevokeCardRequest($revokeCardRequestArgs, $signs = [])
    {
        $createCardRequest = new RevokeCardRequest(...$revokeCardRequestArgs);

        foreach ($signs as $signKey => $sign) {
            $createCardRequest->appendSignature($signKey, Buffer::fromBase64($sign));
        }

        return $createCardRequest;
    }


    public static function createSearchCardRequest($revokeCardRequestArgs, $identities = [])
    {
        $createCardRequest = new SearchCardRequest(...$revokeCardRequestArgs);

        foreach ($identities as $identity) {
            $createCardRequest->appendIdentity($identity);
        }

        return $createCardRequest;
    }

}
