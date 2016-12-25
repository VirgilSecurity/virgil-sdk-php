<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilCards\Mapper\ErrorResponseModelMapper;
use Virgil\Sdk\Client\VirgilCards\Model\ErrorResponseModel;

class ErrorResponseModelMapperTest extends AbstractMapperTest
{
    /**
     * @expectedException \RuntimeException
     *
     * @test
     */
    public function toJson__fromErrorResponseModel__throwsException()
    {
        $errorResponseModel = $this->createErrorResponseModel('30142');


        $this->mapper->toJson($errorResponseModel);


        //expected exception
    }


    /**
     * @test
     */
    public function toModel__fromErrorResponseJsonString__returnsValidErrorResponseModel()
    {
        $errorResponseJsonString = '{"code":"30142"}';
        $expectedErrorResponseModel = $this->createErrorResponseModel('30142');


        $errorResponseModel = $this->mapper->toModel($errorResponseJsonString);


        $this->assertEquals($expectedErrorResponseModel, $errorResponseModel);
    }


    protected function getMapper()
    {
        return new ErrorResponseModelMapper();
    }


    private function createErrorResponseModel($code)
    {
        return new ErrorResponseModel($code);
    }
}
