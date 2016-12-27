<?php
namespace Virgil\Sdk\Tests\Unit\Client;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Client\VirgilClientParams;

class VirgilClientParamsTest extends TestCase
{
    /**
     * @test
     */
    public function getParams__withDefaultValues__returnRealServicesUrls()
    {
        $params = $this->createVirgilClientParams('S&OAhi');


        $cardsServiceAddress = $params->getCardsServiceAddress();
        $identityServiceAddress = $params->getIdentityServiceAddress();
        $readOnlyCardsServiceAddress = $params->getReadOnlyCardsServiceAddress();


        $this->assertEquals('https://cards.virgilsecurity.com', $cardsServiceAddress);
        $this->assertEquals('https://identity.virgilsecurity.com', $identityServiceAddress);
        $this->assertEquals('https://cards-ro.virgilsecurity.com', $readOnlyCardsServiceAddress);
    }


    /**
     * @expectedException \InvalidArgumentException
     *
     * @test
     */
    public function setCardsServiceAddress__withWrongUrl__throwsException()
    {
        $params = $this->createVirgilClientParams('S&OAhi');


        $params->setCardsServiceAddress('wrong-url');


        //throws exception
    }


    /**
     * @expectedException \InvalidArgumentException
     *
     * @test
     */
    public function setReadCardsServiceAddress__withWrongUrl__throwsException()
    {
        $params = $this->createVirgilClientParams('S&OAhi');


        $params->setReadCardsServiceAddress('wrong-url');


        //throws exception
    }


    /**
     * @expectedException \InvalidArgumentException
     *
     * @test
     */
    public function setIdentityServiceAddress__withWrongUrl__throwsException()
    {
        $params = $this->createVirgilClientParams('S&OAhi');


        $params->setIdentityServiceAddress('192.168.0.1');


        //throws exception
    }


    protected function createVirgilClientParams(...$params)
    {
        return new VirgilClientParams(...$params);
    }
}
