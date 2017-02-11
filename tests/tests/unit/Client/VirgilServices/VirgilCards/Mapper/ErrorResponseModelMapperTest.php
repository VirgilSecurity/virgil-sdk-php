<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Mapper\ErrorResponseModelMapper;

use Virgil\Sdk\Tests\Unit\Client\VirgilServices\VirgilCards\Model\ResponseModel;

class ErrorResponseModelMapperTest extends AbstractMapperTest
{
    /**
     * @test
     */
    public function toModel__fromErrorResponseJsonString__returnsValidErrorResponseModel()
    {
        $errorResponseJsonString = '{"code":"30142"}';
        $expectedErrorResponseModel = ResponseModel::createErrorResponseModel('30142');


        $errorResponseModel = $this->mapper->toModel($errorResponseJsonString);


        $this->assertEquals($expectedErrorResponseModel, $errorResponseModel);
    }


    protected function getMapper()
    {
        return new ErrorResponseModelMapper();
    }
}
