<?php
namespace Virgil\Sdk\Tests\Unit\Api;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Contracts\PrivateKeyInterface;
use Virgil\Sdk\Contracts\PublicKeyInterface;

class VirgilKeyTest extends AbstractVirgilKeyTest
{
    /**
     * @test
     */
    public function export__withEmptyPassword__returnsExportedPrivateKey()
    {
        $expectedPrivateKeyExport = new Buffer('exported_private_key');
        $privateKeyMock = $this->createMock(PrivateKeyInterface::class);

        $this->crypto->expects($this->once())
                     ->method('exportPrivateKey')
                     ->with($privateKeyMock)
                     ->willReturn($expectedPrivateKeyExport)
        ;

        $virgilKey = $this->createVirgilKey($privateKeyMock);


        $exportedPrivateKey = $virgilKey->export();


        $this->assertEquals($expectedPrivateKeyExport, $exportedPrivateKey);
    }


    /**
     * @test
     */
    public function exportPublicKey__withEmptyArgs__returnsExportedPublicKey()
    {
        $expectedPublicKeyExport = new Buffer('exported_public_key');
        $privateKeyMock = $this->createMock(PrivateKeyInterface::class);
        $publicKeyMock = $this->createMock(PublicKeyInterface::class);

        $this->crypto->expects($this->once())
                     ->method('extractPublicKey')
                     ->with($privateKeyMock)
                     ->willReturn($publicKeyMock)
        ;

        $this->crypto->expects($this->once())
                     ->method('exportPublicKey')
                     ->with($publicKeyMock)
                     ->willReturn($expectedPublicKeyExport)
        ;

        $virgilKey = $this->createVirgilKey($privateKeyMock);


        $exportedPublicKey = $virgilKey->exportPublicKey();


        $this->assertEquals($expectedPublicKeyExport, $exportedPublicKey);
    }


    /**
     * @test
     */
    public function sign__withContent__returnsValidSignature()
    {
        $content = 'content to sign';
        $expectedSignature = new Buffer('sign');

        $privateKeyMock = $this->createMock(PrivateKeyInterface::class);

        $this->crypto->expects($this->once())
                     ->method('sign')
                     ->with($content)
                     ->willReturn($expectedSignature)
        ;

        $virgilKey = $this->createVirgilKey($privateKeyMock);


        $signature = $virgilKey->sign($content);


        $this->assertEquals($expectedSignature, $signature);
    }


    /**
     * @test
     */
    public function decrypt__withEncryptedContent__returnsOriginalContent()
    {
        $privateKeyMock = $this->createMock(PrivateKeyInterface::class);
        $encryptedContentBuffer = new Buffer('encrypted');
        $expectedOriginalContent = 'original';

        $this->crypto->expects($this->once())
                     ->method('decrypt')
                     ->with($encryptedContentBuffer, $privateKeyMock)
                     ->willReturn($expectedOriginalContent)
        ;

        $virgilKey = $this->createVirgilKey($privateKeyMock);


        $decryptedContent = $virgilKey->decrypt($encryptedContentBuffer);


        $this->assertEquals($expectedOriginalContent, $decryptedContent);

    }


    /**
     * @test
     */
    public function signThenEncrypt__withContentAndManyRecipients__returnsSignedAndEncryptedContent()
    {
        $bobPrivateKeyMock = $this->createMock(PrivateKeyInterface::class);
        $alicePublicKeyMock = $this->createMock(PublicKeyInterface::class);
        $alexPublicKeyMock = $this->createMock(PublicKeyInterface::class);

        $expectedSignedAndEncryptedContent = new Buffer('encrypted and signed');
        $content = 'original content';

        $aliceVirgilCard = $this->createVirgilCard($alicePublicKeyMock);
        $alexVirgilCard = $this->createVirgilCard($alexPublicKeyMock);

        $this->crypto->expects($this->once())
                     ->method('signThenEncrypt')
                     ->with($content, $bobPrivateKeyMock, [$alicePublicKeyMock, $alexPublicKeyMock])
                     ->willReturn($expectedSignedAndEncryptedContent)
        ;

        $virgilKey = $this->createVirgilKey($bobPrivateKeyMock);


        $signedAndEncryptedContent = $virgilKey->signThenEncrypt($content, [$aliceVirgilCard, $alexVirgilCard]);


        $this->assertEquals($expectedSignedAndEncryptedContent, $signedAndEncryptedContent);
    }


    /**
     * @test
     */
    public function decryptThenVerify__withEncryptedAndSignedContentAndVerifierCard__returnsVerifiedOriginalContent()
    {
        $encryptedAndSignedContent = new Buffer('encrypted with sign content');
        $expectedContent = new Buffer('decrypted content');

        $alicePublicKeyMock = $this->createMock(PublicKeyInterface::class);
        $aliceVirgilCard = $this->createVirgilCard($alicePublicKeyMock);

        $bobPrivateKeyMock = $this->createMock(PrivateKeyInterface::class);
        $bobVirgilKey = $this->createVirgilKey($bobPrivateKeyMock);

        $this->crypto->expects($this->once())
                     ->method('decryptThenVerify')
                     ->with($encryptedAndSignedContent, $bobPrivateKeyMock, $alicePublicKeyMock)
                     ->willReturn($expectedContent)
        ;


        $decryptedAndVerifiedContent = $bobVirgilKey->decryptThenVerify($encryptedAndSignedContent, $aliceVirgilCard);


        $this->assertEquals($expectedContent, $decryptedAndVerifiedContent);
    }
}
