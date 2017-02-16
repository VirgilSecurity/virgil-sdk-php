<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractJsonModelMapper;

use Virgil\Sdk\Tests\BaseTestCase;

abstract class AbstractMapperTest extends BaseTestCase
{
    const CARD_SIGNED_REQUEST_JSON_FORMAT = '{"content_snapshot":"%s","meta":%s}';
    const CARD_SIGNED_RESPONSE_JSON_FORMAT = '{"id":"%s","content_snapshot":"%s","meta":%s}';

    /** @var AbstractJsonModelMapper $mapper */
    protected $mapper;


    public function setUp()
    {
        $this->mapper = $this->getMapper();
    }


    protected abstract function getMapper();


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
