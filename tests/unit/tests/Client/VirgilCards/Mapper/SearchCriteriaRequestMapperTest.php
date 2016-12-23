<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards\Mapper;


use RuntimeException;
use Virgil\Sdk\Client\VirgilCards\Mapper\SearchCriteriaRequestMapper;
use Virgil\Sdk\Client\VirgilCards\SearchCriteria;
use Virgil\Sdk\Client\Requests\Constants\CardScopes;

class SearchCriteriaRequestMapperTest extends AbstractMapperTest
{
    /**
     * @dataProvider searchCardDataProvider
     *
     * @param $expectedJson
     * @param $args
     *
     * @test
     */
    public function toJson__fromSearchCriteria__returnsValidJson($expectedJson, $args)
    {
        $searchCriteria = $this->createSearchCriteria(...$args);


        $actualJson = $this->mapper->toJson($searchCriteria);


        $this->assertEquals($expectedJson, $actualJson);
    }


    /**
     * @expectedException RuntimeException
     *
     * @test
     */
    public function toModel__fromSearchCriteriaRequestJsonString__throwsException()
    {
        $searchCriteriaJson = '{"identities":["user2@virgilsecurity.com","another.user2@virgilsecurity.com"]}';


        $this->mapper->toModel($searchCriteriaJson);


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


    protected function createSearchCriteriaRequestMapper()
    {
        return new SearchCriteriaRequestMapper();
    }


    protected function createSearchCriteria($identities, $identityType = null, $scope = null)
    {
        return new SearchCriteria($identities, $identityType, $scope);
    }


    protected function getMapper()
    {
        return $this->createSearchCriteriaRequestMapper();
    }
}
