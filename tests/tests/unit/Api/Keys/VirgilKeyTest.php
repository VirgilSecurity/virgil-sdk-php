<?php
namespace Virgil\Sdk\Tests\Unit\Api\Keys;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Contracts\BufferInterface;
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
     * @dataProvider signVirgilKeyDataProvider
     *
     * @param $content
     * @param $stringContentRepresentation
     *
     * @test
     */
    public function sign__withMixedContent__returnsValidSignature(
        $content,
        $stringContentRepresentation
    ) {
        $expectedSignature = new Buffer('sign');

        $privateKeyMock = $this->createMock(PrivateKeyInterface::class);

        $this->crypto->expects($this->once())
                     ->method('sign')
                     ->with($stringContentRepresentation)
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
    public function decrypt__withBase64EncodedCipher__callsVirgilCryptoDecryptWithBufferCipherRepresentation()
    {
        $cipher = new Buffer('encrypted');
        $base64cipher = 'ZW5jcnlwdGVk';
        $originalString = 'original';

        $privateKeyMock = $this->createMock(PrivateKeyInterface::class);

        $this->crypto->expects($this->once())
                     ->method('decrypt')
                     ->with($cipher, $privateKeyMock)
                     ->willReturn($originalString)
        ;

        $virgilKey = $this->createVirgilKey($privateKeyMock);


        $decryptedContent = $virgilKey->decrypt($base64cipher);


        $this->assertEquals($originalString, $decryptedContent);
    }


    /**
     * @dataProvider signThenEncryptDataProvider
     *
     * @param mixed           $content
     * @param BufferInterface $expectedSignedAndEncryptedContent
     *
     * @test
     */
    public function signThenEncrypt__withContentAndManyRecipients__returnsSignedAndEncryptedContent(
        $content,
        $expectedSignedAndEncryptedContent
    ) {
        $bobPrivateKeyMock = $this->createMock(PrivateKeyInterface::class);
        $alicePublicKeyMock = $this->createMock(PublicKeyInterface::class);
        $alexPublicKeyMock = $this->createMock(PublicKeyInterface::class);

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


    /**
     * @test
     */
    public function decryptThenVerify__withBase64EncodedCipher__callsVirgilCryptodecryptThenVerifyWithBufferCipherRepresentation(
    )
    {
        $encryptedAndSignedContent = new Buffer('encrypted with sign content');
        $expectedContent = new Buffer('decrypted content');
        $base64EncodedCipherContent = 'ZW5jcnlwdGVkIHdpdGggc2lnbiBjb250ZW50';

        $alicePublicKeyMock = $this->createMock(PublicKeyInterface::class);
        $aliceVirgilCard = $this->createVirgilCard($alicePublicKeyMock);

        $bobPrivateKeyMock = $this->createMock(PrivateKeyInterface::class);
        $bobVirgilKey = $this->createVirgilKey($bobPrivateKeyMock);

        $this->crypto->expects($this->once())
                     ->method('decryptThenVerify')
                     ->with($encryptedAndSignedContent, $bobPrivateKeyMock, $alicePublicKeyMock)
                     ->willReturn($expectedContent)
        ;


        $decryptedAndVerifiedContent = $bobVirgilKey->decryptThenVerify($base64EncodedCipherContent, $aliceVirgilCard);


        $this->assertEquals($expectedContent, $decryptedAndVerifiedContent);
    }


    public function signVirgilKeyDataProvider()
    {
        return [
            [new Buffer('content to sign'), 'content to sign'],
            ['content to sign', 'content to sign'],
            [Buffer::fromHex('636f6e74656e7420746f207369676e'), 'content to sign'],
            [Buffer::fromBase64('Y29udGVudCB0byBzaWdu'), 'content to sign'],
        ];
    }


    public function signThenEncryptDataProvider()
    {
        return [
            ['original content', new Buffer('encrypted and signed')],
            [new Buffer('original content'), new Buffer('encrypted and signed')],
        ];
    }
}
