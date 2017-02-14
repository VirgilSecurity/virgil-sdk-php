<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\SearchRequestModelMapper;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model\RequestModel;

class SearchRequestModelMapperTest extends AbstractVirgilCardsMapperTest
{
    /**
     * @dataProvider searchCardDataProvider
     *
     * @param $expectedJson
     * @param $args
     *
     * @test
     */
    public function toJson__fromSearchRequestModel__returnsValidJson($expectedJson, $args)
    {
        $searchRequestModel = RequestModel::createSearchRequestModel(...$args);


        $actualJson = $this->mapper->toJson($searchRequestModel);


        $this->assertEquals($expectedJson, $actualJson);
    }


    /**
     * @expectedException \Virgil\Sdk\Exceptions\MethodIsDisabledException
     *
     * @test
     */
    public function toModel__fromSearchRequestModelJsonString__throwsException()
    {
        $searchRequestModelJson = '{"identities":["user2@virgilsecurity.com","another.user2@virgilsecurity.com"]}';


        $this->mapper->toModel($searchRequestModelJson);


        //expected exception
    }


    public function searchCardDataProvider()
    {
        return [
            [
                '{"identities":["user@virgilsecurity.com","another.user@virgilsecurity.com"],"identity_type":"email","scope":"global"}',
                [
                    ['user@virgilsecurity.com', 'another.user@virgilsecurity.com'],
                    'email',
                    CardScopes::TYPE_GLOBAL,
                ],
            ],
            [
                '{"identities":["user2@virgilsecurity.com","another.user2@virgilsecurity.com"]}',
                [
                    ['user2@virgilsecurity.com', 'another.user2@virgilsecurity.com'],
                ],
            ],
        ];
    }


    protected function createSearchRequestModelMapper()
    {
        return new SearchRequestModelMapper();
    }


    protected function getMapper()
    {
        return $this->createSearchRequestModelMapper();
    }
}
