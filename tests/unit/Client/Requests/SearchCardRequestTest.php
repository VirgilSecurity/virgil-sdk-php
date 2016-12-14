<?php
namespace Virgil\Tests\Unit\Client\Requests;


use PHPUnit\Framework\TestCase;
use Virgil\Sdk\Client\Constants\CardScopes;
use Virgil\Sdk\Client\Requests\SearchCardRequest;
use Virgil\Sdk\Client\VirgilCards\SearchCriteria;

class SearchCardRequestTest extends TestCase
{
    /**
     * @test
     */
    public function getSearchCriteria_WithAppendedIdentities_ReturnsSearchCriteria()
    {
        $expectedSearchCriteria = new SearchCriteria(['qwe', 'rty'], 'email', CardScopes::TYPE_APPLICATION);

        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest->appendIdentity('qwe')
                          ->appendIdentity('rty')
        ;

        $this->assertEquals($expectedSearchCriteria, $searchCardRequest->getSearchCriteria());
    }


    /**
     * @test
     */
    public function getSearchCriteria_WithNoIdentityTypeAndScope_ReturnsSearchCriteria()
    {
        $expectedSearchCriteria = new SearchCriteria(['qwe']);

        $searchCardRequest = new SearchCardRequest();
        $searchCardRequest->appendIdentity('qwe');

        $this->assertEquals($expectedSearchCriteria, $searchCardRequest->getSearchCriteria());
    }


    /**
     * @test
     */
    public function getSearchCriteria_WithDuplicatedAppendedIdentities_ReturnsSearchCriteriaWithNoDuplicateIdentity()
    {
        $expectedSearchCriteria = new SearchCriteria(['qwe'], 'email', CardScopes::TYPE_APPLICATION);

        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest->appendIdentity('qwe')
                          ->appendIdentity('qwe')
        ;

        $this->assertEquals($expectedSearchCriteria, $searchCardRequest->getSearchCriteria());
    }


    /**
     * @test
     *
     * @expectedException \Virgil\Sdk\Client\Requests\SearchCardRequestException
     */
    public function getSearchCriteria_WithDidNotAppendIdentities_ThrowsException()
    {
        //expected exception on empty identities

        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);

        $searchCardRequest->getSearchCriteria();
    }


    /**
     * @test
     */
    public function getSearchCriteria_WithSetAllIdentities_ReturnsSearchCriteria()
    {
        $expectedSearchCriteria = new SearchCriteria(['qwe', 'rty'], 'email', CardScopes::TYPE_APPLICATION);

        $searchCardRequest = new SearchCardRequest('email', CardScopes::TYPE_APPLICATION);
        $searchCardRequest->setIdentities(['qwe', 'rty']);

        $this->assertEquals($expectedSearchCriteria, $searchCardRequest->getSearchCriteria());
    }

}
