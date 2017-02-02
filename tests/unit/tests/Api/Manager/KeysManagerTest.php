<?php
namespace Virgil\Sdk\Tests\Unit\Api\Manager;


use Virgil\Sdk\Contracts\PrivateKeyInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

class KeysManagerTest extends AbstractKeysManagerTest
{
    /**
     * @test
     */
    public function generate__whenCalls__returnsNewVirgilKeyWithValidPrivateKey()
    {
        $privateKeyMock = $this->createMock(PrivateKeyInterface::class);
        $publicKeyMock = $this->createMock(PublicKeyInterface::class);

        $expectedVirgilKey = $this->createVirgilKey($privateKeyMock);
        $cryptoKeyPair = $this->createCryptoKeyPair($publicKeyMock, $privateKeyMock);

        $this->crypto->expects($this->once())
                     ->method('generateKeys')
                     ->willReturn($cryptoKeyPair)
        ;


        $generatedVirgilKey = $this->keysManager->generate();


        $this->assertAttributeSame($privateKeyMock, 'privateKey', $generatedVirgilKey);
        $this->assertNotSame($expectedVirgilKey, $generatedVirgilKey);
    }


    /**
     * @dataProvider storedKeysDataProvider
     *
     * @param $keyName
     * @param $keyContent
     *
     * @test
     */
    public function load__withExistingKey__returnsStoredVirgilKey($keyName, $keyContent)
    {
        $privateKeyMock = $this->createMock(PrivateKeyInterface::class);
        $expectedVirgilKey = $this->createVirgilKey($privateKeyMock);

        $this->crypto->expects($this->once())
                     ->method('importPrivateKey')
                     ->with($keyContent)
                     ->willReturn($privateKeyMock)
        ;


        $loadedVirgilKey = $this->keysManager->load($keyName);

        $this->assertAttributeSame($privateKeyMock, 'privateKey', $loadedVirgilKey);
        $this->assertNotSame($expectedVirgilKey, $loadedVirgilKey);
    }


    /**
     * @expectedException \Virgil\Sdk\Api\Manager\VirgilKeyIsNotFoundException
     *
     * @test
     */
    public function load__withNotExistingKey__throwsException()
    {
        $keyName = 'alex_key_name'; //does not exist in storage


        $this->keysManager->load($keyName);


        //expected exception
    }


    /**
     * @dataProvider storedKeysDataProvider
     *
     * @param $keyName
     *
     * @test
     */
    public function destroy__withExistingKeyName__removesVirgilKey($keyName)
    {


        $this->keysManager->destroy($keyName);


        $this->assertKeyInStorage($keyName, false);
    }


    /**
     * @expectedException \Virgil\Sdk\Api\Manager\VirgilKeyIsNotFoundException
     *
     * @test
     */
    public function destroy__withNotExistingKey__throwsException()
    {
        $keyName = 'alex_key_name'; //does not exist in storage

        $this->keysManager->destroy($keyName);


        //expected exception
    }


    /**
     * @return array
     */
    public function storedKeysDataProvider()
    {
        return $this->getStoredKeys();
    }


    /**
     * @return array
     */
    protected function getStoredKeys()
    {
        return [
            ['alice_key_name', 'alice_key_content'],
            ['bob_key_name', 'bob_key_content'],
        ];
    }
}
