<?php
namespace Virgil\Sdk\Tests\Integration\Cryptography;


use PHPUnit\Framework\TestCase;
use Virgil\Sdk\Buffer;
use Virgil\Sdk\Cryptography\VirgilCrypto;

class VirgilCryptoCompatibilityTest extends TestCase
{
    const COMPATIBILITY_FILE_NAME = 'sdk_compatibility_data.json';


    /**
     * @dataProvider getEncryptedContentWithValidRecipientPrivateKeyDataProvider
     *
     * @param $private_key
     * @param $original_data
     * @param $cipher_data
     *
     * @test
     */
    public function decrypt__withValidRecipientPrivateKey__returnsOriginalData(
        $private_key,
        $original_data,
        $cipher_data
    ) {
        $virgilCrypto = $this->createVirgilCrypto();
        $recipientPrivateKey = $virgilCrypto->importPrivateKey(Buffer::fromBase64($private_key));
        $expectedOriginalData = base64_decode($original_data);


        $actualDecryptedData = $virgilCrypto->decrypt(Buffer::fromBase64($cipher_data), $recipientPrivateKey);


        $this->assertEquals($expectedOriginalData, $actualDecryptedData->getData());
    }


    /**
     * @dataProvider getEncryptedContentWithValidRecipientAndSignerPrivateKeyDataProvider
     *
     * @param $private_key
     * @param $signer_private_key
     * @param $original_data
     * @param $cipher_data
     *
     * @test
     */
    public function decryptThenVerify__withValidArguments__returnsVerifiedOriginalData(
        $private_key,
        $original_data,
        $cipher_data,
        $signer_private_key
    ) {
        $virgilCrypto = $this->createVirgilCrypto();
        $expectedOriginalData = base64_decode($original_data);
        $recipientPrivateKey = $virgilCrypto->importPrivateKey(Buffer::fromBase64($private_key));
        $signerPrivateKey = $virgilCrypto->importPrivateKey(Buffer::fromBase64($signer_private_key));
        $signerPublicKey = $virgilCrypto->extractPublicKey($signerPrivateKey);


        $actualDecryptedData = $virgilCrypto->decryptThenVerify(
            Buffer::fromBase64($cipher_data),
            $recipientPrivateKey,
            $signerPublicKey
        );


        $this->assertEquals($expectedOriginalData, $actualDecryptedData->getData());
    }


    /**
     * @dataProvider getSignatureForOriginalContentAndSignerPrivateKeyDataProvider
     *
     * @param $private_key
     * @param $original_data
     * @param $signature
     *
     * @test
     */
    public function sign__withValidSignerPrivateKey__returnsCorrectSignature($private_key, $original_data, $signature)
    {
        $virgilCrypto = $this->createVirgilCrypto();
        $signerPrivateKey = $virgilCrypto->importPrivateKey(Buffer::fromBase64($private_key));
        $expectedSignature = base64_decode($signature);
        $expectedOriginalData = base64_decode($original_data);


        $actualSignature = $virgilCrypto->sign($expectedOriginalData, $signerPrivateKey);


        $this->assertEquals($expectedSignature, $actualSignature->getData());
    }


    public function getEncryptedContentWithValidRecipientPrivateKeyDataProvider()
    {
        return $this->createCompatibilityDataProvider()
                    ->getEncryptArgumentsSetWithOriginalContent()
            ;
    }


    public function getEncryptedContentWithValidRecipientAndSignerPrivateKeyDataProvider()
    {
        return $this->createCompatibilityDataProvider()
                    ->getSignThenEncryptRecipientsData()
            ;
    }


    public function getSignatureForOriginalContentAndSignerPrivateKeyDataProvider()
    {
        return $this->createCompatibilityDataProvider()
                    ->getGenerateSignatureData()
            ;
    }


    private function createVirgilCrypto()
    {
        return new VirgilCrypto();
    }


    private function createCompatibilityDataProvider()
    {
        return new CompatibilityDataProvider(
            VIRGIL_FIXTURE_PATH . DIRECTORY_SEPARATOR . self::COMPATIBILITY_FILE_NAME
        );
    }

}
