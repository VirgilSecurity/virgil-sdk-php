<?php
namespace Virgil\Sdk\Tests\Unit\Api\Keys;


use Virgil\Sdk\Api\Cards\VirgilCardInterface;

use Virgil\Sdk\Api\Keys\VirgilKey;

use Virgil\Sdk\Contracts\CryptoInterface;
use Virgil\Sdk\Contracts\PrivateKeyInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

use Virgil\Sdk\Tests\Unit\Api\AbstractVirgilApiContextTest;
use Virgil\Sdk\Tests\Unit\Api\Storage\MemoryKeyStorage;

abstract class AbstractVirgilKeyTest extends AbstractVirgilApiContextTest
{
    /**
     * @inheritdoc
     */
    protected function createKeyStorage()
    {
        return new MemoryKeyStorage();
    }


    /**
     * @param PrivateKeyInterface $privateKey
     *
     * @return VirgilKey
     */
    protected function createVirgilKey(PrivateKeyInterface $privateKey)
    {
        return new VirgilKey($this->virgilApiContext, $privateKey);
    }


    /**
     * @param PublicKeyInterface $publicKey
     *
     * @return VirgilCardInterface
     */
    protected function createVirgilCard(PublicKeyInterface $publicKey)
    {
        $virgilCard = $this->createMock(VirgilCardInterface::class);

        $virgilCard->expects($this->any())
                   ->method('getPublicKey')
                   ->willReturn($publicKey)
        ;

        return $virgilCard;
    }
}
