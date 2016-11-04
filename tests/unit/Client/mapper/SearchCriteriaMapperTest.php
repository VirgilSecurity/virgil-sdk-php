<?php

namespace Virgil\Tests\Unit\Client\Mapper;


use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\RuntimeException;
use Virgil\SDK\Client\CardScope;
use Virgil\SDK\Client\Mapper\SearchCriteriaMapper;
use Virgil\SDK\Client\Model\SearchCriteria;

class SearchCriteriaTest extends TestCase
{
    /**
     * @dataProvider searchCardDataProvider
     * @param $expectedJson
     * @param $args
     */
    public function testMapSearchCriteriaToJson($expectedJson, $args)
    {
        $mapper = new SearchCriteriaMapper();
        $model = new SearchCriteria(...$args);
        $this->assertEquals($expectedJson, $mapper->toJson($model));
    }


    /**
     * @dataProvider searchCardDataProvider
     * @expectedException RuntimeException
     * @param $json
     */
    public function testMapSearchCriteriaToModel($json)
    {
        $mapper = new SearchCriteriaMapper();
        $mapper->toModel($json);
    }

    public function searchCardDataProvider()
    {
        return [
            ['{"identities":["user@virgilsecurity.com","another.user@virgilsecurity.com"],"identity_type":"email","scope":"global"}',
                [['user@virgilsecurity.com', 'another.user@virgilsecurity.com'], 'email', CardScope::TYPE_GLOBAL]
            ],
            ['{"identities":["user2@virgilsecurity.com","another.user2@virgilsecurity.com"]}',
                [['user2@virgilsecurity.com', 'another.user2@virgilsecurity.com']]
            ]
        ];
    }
}