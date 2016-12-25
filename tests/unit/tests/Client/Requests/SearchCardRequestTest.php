<?php
namespace Virgil\Sdk\Tests\Unit\Client\Requests;


use PHPUnit\Framework\TestCase;
use Virgil\Sdk\Client\Requests\Constants\CardScopes;
use Virgil\Sdk\Client\Requests\SearchCardRequest;
use Virgil\Sdk\Client\VirgilCards\SearchCriteria;

class SearchCardRequestTest extends TestCase
{
    /**
     * @test
     */
    public function getSearchCriteria__withAppendedIdentities__returnsValidSearchCriteria()
    {
        $expectedSearchCriteria = new SearchCriteria(['qwe', 'rty'], 'email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest->appendIdentity('qwe')
                          ->appendIdentity('rty')
        ;


        $actualSearchCriteria = $searchCardRequest->getSearchCriteria();


        $this->assertEquals($expectedSearchCriteria, $actualSearchCriteria);
    }


    /**
     * @test
     */
    public function getSearchCriteria__withNoIdentityTypeAndScope__returnsValidSearchCriteria()
    {
        $expectedSearchCriteria = new SearchCriteria(['qwe']);
        $searchCardRequest = new SearchCardRequest();
        $searchCardRequest->appendIdentity('qwe');


        $actualSearchCriteria = $searchCardRequest->getSearchCriteria();


        $this->assertEquals($expectedSearchCriteria, $actualSearchCriteria);
    }


    /**
     * @test
     */
    public function getSearchCriteria__withDuplicatedAppendedIdentities__returnsSearchCriteriaWithNoDuplicateIdentity()
    {
        $expectedSearchCriteria = new SearchCriteria(['qwe'], 'email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest->appendIdentity('qwe')
                          ->appendIdentity('qwe')
        ;


        $actualSearchCriteria = $searchCardRequest->getSearchCriteria();


        $this->assertEquals($expectedSearchCriteria, $actualSearchCriteria);
    }


    /**
     * @test
     *
     * @expectedException \Virgil\Sdk\Client\Requests\SearchCardRequestException
     */
    public function getSearchCriteria__withDidNotAppendIdentities__throwsException()
    {
        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);


        $searchCardRequest->getSearchCriteria();


        //expected exception on empty identities

    }


    /**
     * @test
     */
    public function getSearchCriteria__withSetAllIdentities__returnsSearchCriteria()
    {
        $expectedSearchCriteria = new SearchCriteria(['qwe', 'rty'], 'email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest->setIdentities(['qwe', 'rty']);


        $actualSearchCriteria = $searchCardRequest->getSearchCriteria();


        $this->assertEquals($expectedSearchCriteria, $actualSearchCriteria);
    }

}
