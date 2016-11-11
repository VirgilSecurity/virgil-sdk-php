<?php

namespace Virgil\Tests\Unit\Client;


use PHPUnit\Framework\TestCase;
use Virgil\SDK\Client\VirgilClientParams;

class VirgilClientParamsTest extends TestCase
{
    public function testParams()
    {
        $params = new VirgilClientParams('S&OAhi');
        $this->assertEquals('https://cards.virgilsecurity.com', $params->getCardsServiceAddress());
        $this->assertEquals('https://identity.virgilsecurity.com', $params->getIdentityServiceAddress());
        $this->assertEquals('https://cards-ro.virgilsecurity.com', $params->getReadOnlyCardsServiceAddress());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetCardsServiceAddress()
    {
        $params = new VirgilClientParams('S&OAhi');
        $params->setCardsServiceAddress('wrong-url');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetReadOnlyCardsServiceAddress()
    {
        $params = new VirgilClientParams('S&OAhi');
        $params->setReadOnlyCardsServiceAddress('wrong-url');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetIdentityServiceAddress()
    {
        $params = new VirgilClientParams('S&OAhi');
        $params->setIdentityServiceAddress('192.168.0.1');
    }
}