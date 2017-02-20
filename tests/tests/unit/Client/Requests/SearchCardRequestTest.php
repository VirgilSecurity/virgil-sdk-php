<?php
namespace Virgil\Sdk\Tests\Unit\Client\Requests;


use Virgil\Sdk\Tests\BaseTestCase;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\Requests\SearchCardRequest;

use Virgil\Sdk\Client\VirgilServices\VirgilCards\Model\SearchRequestModel;

class SearchCardRequestTest extends BaseTestCase
{
    /**
     * @test
     */
    public function getSearchModel__withAppendedIdentities__returnsValidSearchModel()
    {
        $expectedSearchModel = new SearchRequestModel(['qwe', 'rty'], 'email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest->appendIdentity('qwe')
                          ->appendIdentity('rty')
        ;


        $actualSearchModel = $searchCardRequest->getRequestModel();


        $this->assertEquals($expectedSearchModel, $actualSearchModel);
    }


    /**
     * @test
     */
    public function getSearchModel__withNoIdentityTypeAndScope__returnsValidSearchModel()
    {
        $expectedSearchModel = new SearchRequestModel(['qwe']);
        $searchCardRequest = new SearchCardRequest();
        $searchCardRequest->appendIdentity('qwe');


        $actualSearchModel = $searchCardRequest->getRequestModel();


        $this->assertEquals($expectedSearchModel, $actualSearchModel);
    }


    /**
     * @test
     */
    public function getSearchModel__withDuplicatedAppendedIdentities__returnsSearchModelWithNoDuplicateIdentity()
    {
        $expectedSearchModel = new SearchRequestModel(['qwe'], 'email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest->appendIdentity('qwe')
                          ->appendIdentity('qwe')
        ;


        $actualSearchModel = $searchCardRequest->getRequestModel();


        $this->assertEquals($expectedSearchModel, $actualSearchModel);
    }


    /**
     * @test
     *
     * @expectedException \Virgil\Sdk\Client\Requests\SearchCardRequestException
     */
    public function getSearchModel__withDidNotAppendIdentities__throwsException()
    {
        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);


        $searchCardRequest->getRequestModel();


        //expected exception on empty identities

    }


    /**
     * @test
     */
    public function getSearchModel__withSetAllIdentities__returnsSearchModel()
    {
        $expectedSearchModel = new SearchRequestModel(['qwe', 'rty'], 'email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest->setIdentities(['qwe', 'rty']);


        $actualSearchModel = $searchCardRequest->getRequestModel();


        $this->assertEquals($expectedSearchModel, $actualSearchModel);
    }

}
