<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Mapper\AbstractMapperTest;

abstract class AbstractVirgilCardsMapperTest extends AbstractMapperTest
{
    const CARD_SIGNED_REQUEST_JSON_FORMAT = '{"content_snapshot":"%s","meta":%s}';

    const CARD_SIGNED_RESPONSE_JSON_FORMAT = '{"id":"%s","content_snapshot":"%s","meta":%s}';


    protected function createSignedCardRequestJson($format, $cardContentJson, $cardMetaJson)
    {
        return vsprintf(
            $format,
            [
                base64_encode($cardContentJson),
                $cardMetaJson,
            ]
        );
    }


    protected function createSignedCardResponseJson($format, $id, $cardContentJson, $cardMetaJson)
    {
        return vsprintf(
            $format,
            [
                $id,
                base64_encode($cardContentJson),
                $cardMetaJson,
            ]
        );
    }
}
