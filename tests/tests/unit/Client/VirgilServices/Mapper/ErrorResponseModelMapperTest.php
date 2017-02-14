<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\Mapper;


use Virgil\Sdk\Tests\Unit\Client\VirgilServices\Model\ResponseModel;

class ErrorResponseModelMapperTest extends AbstractMapperTest
{
    /**
     * @test
     */
    public function toModel__fromErrorResponseJsonString__returnExpectedErrorResponseModel()
    {
        $errorResponseJsonString = '{"code":"123"}';
        $expectedErrorResponseModel = ResponseModel::createErrorResponseModel('123', '123 error message');


        $errorResponseModel = $this->mapper->toModel($errorResponseJsonString);


        $this->assertEquals($expectedErrorResponseModel, $errorResponseModel);
    }


    /**
     * @test
     */
    public function toModel__fromEmptyString__returnEmptyErrorResponseModel()
    {
        $errorResponseJsonString = ' ';
        $expectedErrorResponseModel = ResponseModel::createErrorResponseModel('', '');


        $errorResponseModel = $this->mapper->toModel($errorResponseJsonString);


        $this->assertEquals($expectedErrorResponseModel, $errorResponseModel);
    }


    protected function getMapper()
    {
        return new ErrorResponseModelMapper();
    }
}
