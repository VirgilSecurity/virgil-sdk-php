<?php
namespace Virgil\Tests\Unit\Client\VirgilCards\Mapper;


use DateTime;

use RuntimeException;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\VirgilCards\Mapper\SearchCriteriaResponseMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SignedResponseModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\CardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\DeviceInfoModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedResponseModel;

use Virgil\Sdk\Tests\Unit\Client\VirgilCards\Mapper\AbstractMapperTest;

class SearchCriteriaResponseMapperTest extends AbstractMapperTest
{
    /**
     * @dataProvider searchCriteriaResponseDataProvider
     *
     * @expectedException RuntimeException
     *
     * @param $searchCriteriaResponseDataSet
     * @param $searchCriteriaResponseJsonDataSet
     *
     * @test
     */
    public function toJson__fromSearchCriteriaResponseModel__throwsException(
        $searchCriteriaResponseDataSet,
        $searchCriteriaResponseJsonDataSet
    ) {
        $searchCriteriaResponse = $this->createSearchCriteriaResponse($searchCriteriaResponseDataSet);


        $this->mapper->toJson($searchCriteriaResponse);


        //expected exception
    }


    /**
     * @dataProvider searchCriteriaResponseDataProvider
     *
     * @param $searchCriteriaResponseDataSet
     * @param $searchCriteriaResponseJsonDataSet
     *
     * @test
     */
    public function toModel__fromSearchCriteriaResponseModelJsonString__returnsValidSearchCriteriaResponseModel(
        $searchCriteriaResponseDataSet,
        $searchCriteriaResponseJsonDataSet
    ) {
        $expectedSearchCriteriaResponse = $this->createSearchCriteriaResponse($searchCriteriaResponseDataSet);
        $searchCriteriaResponseJson = $this->createSearchCriteriaResponseJson(...$searchCriteriaResponseJsonDataSet);


        $searchCriteriaResponse = $this->mapper->toModel($searchCriteriaResponseJson);


        $this->assertEquals($expectedSearchCriteriaResponse, $searchCriteriaResponse);
    }


    public function searchCriteriaResponseDataProvider()
    {
        return [
            [
                [
                    [
                        'model-id-1',
                        'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwiZGF0YSI6eyJjdXN0b21EYXRhIjoicXdlcnR5In0sInNjb3BlIjoiZ2xvYmFsIiwiaW5mbyI6eyJkZXZpY2UiOiJpUGhvbmU2cyIsImRldmljZV9uYW1lIjoiU3BhY2UgZ3JleSBvbmUifX0=',
                        [
                            'alice2',
                            'member',
                            'public-key-2',
                            CardScopes::TYPE_GLOBAL,
                            ['customData' => 'qwerty'],
                            new DeviceInfoModel('iPhone6s', 'Space grey one'),
                        ],
                        [
                            ['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'],
                            new DateTime('2016-11-04T13:16:17+0000'),
                            'v4',
                        ],
                    ],
                    [
                        'model-id-2',
                        'eyJpZGVudGl0eSI6ImFsaWNlMiIsImlkZW50aXR5X3R5cGUiOiJtZW1iZXIiLCJwdWJsaWNfa2V5IjoicHVibGljLWtleS0yIiwic2NvcGUiOiJnbG9iYWwifQ==',
                        ['alice2', 'member', 'public-key-2', CardScopes::TYPE_GLOBAL],
                        [
                            ['sign-id-3' => '_sign3', 'sign-id-4' => '_sign4'],
                            new DateTime('2016-11-04T13:16:17+0000'),
                            'v4',
                        ],
                    ],
                ],
                [
                    '[%s,%s]',
                    [
                        [
                            self::CARD_SIGNED_RESPONSE_JSON_FORMAT,
                            'model-id-1',
                            '{"identity":"alice2","identity_type":"member","public_key":"public-key-2","data":{"customData":"qwerty"},"scope":"global","info":{"device":"iPhone6s","device_name":"Space grey one"}}',
                            '{"created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}',
                        ],
                        [
                            self::CARD_SIGNED_RESPONSE_JSON_FORMAT,
                            'model-id-2',
                            '{"identity":"alice2","identity_type":"member","public_key":"public-key-2","scope":"global"}',
                            '{"created_at":"2016-11-04T13:16:17+0000","card_version":"v4","signs":{"sign-id-3":"_sign3","sign-id-4":"_sign4"}}',
                        ],
                    ],
                ],
            ],
        ];
    }


    protected function getMapper()
    {
        return new SearchCriteriaResponseMapper(new SignedResponseModelMapper());
    }


    private function createSearchCriteriaResponse($searchCriteriaDataSet)
    {
        $searchCriteriaDataToSignedResponseModel = function ($id, $contentSnapshot, $contentData, $metaData) {
            return new SignedResponseModel(
                $id, $contentSnapshot, new CardContentModel(...$contentData), new SignedResponseMetaModel(...$metaData)
            );
        };

        $result = [];

        foreach ($searchCriteriaDataSet as $searchCriteriaData) {
            $result[] = call_user_func_array($searchCriteriaDataToSignedResponseModel, $searchCriteriaData);
        }

        return $result;
    }


    private function createSearchCriteriaResponseJson($format, $searchCriteriaResponseJsonDataSet)
    {
        $searchCriteriaJsonDataSetToString = function ($searchCriteriaResponseJsonData) {
            return $this->createSignedCardResponseJson(...$searchCriteriaResponseJsonData);
        };

        $result = [];

        foreach ($searchCriteriaResponseJsonDataSet as $searchCriteriaResponseJsonData) {
            $result[] = call_user_func($searchCriteriaJsonDataSetToString, $searchCriteriaResponseJsonData);
        }


        return vsprintf($format, $result);
    }
}
