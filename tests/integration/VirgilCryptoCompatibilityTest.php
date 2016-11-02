<?php
namespace Virgil\Tests\Integration\Cryptography;

use PHPUnit\Framework\TestCase;
use Virgil\SDK\Buffer;
use Virgil\SDK\Cryptography\VirgilCrypto;

class VirgilCryptoCompatibilityTest extends TestCase
{
    public $sdkCompatibilityData;

    public function __construct($name, $data = [], $dataName)
    {
        parent::__construct($name, $data, $dataName);
        $fixtures = file_get_contents(FIXTURE_PATH . DIRECTORY_SEPARATOR . 'sdk_compatibility_data.json');
        $this->sdkCompatibilityData = json_decode($fixtures, true);
    }

    /**
     * @dataProvider getEncryptRecipientsDataProvider
     * @param $private_key
     * @param $original_data
     * @param $cipher_data
     */
    public function testEncryptRecipient($private_key, $original_data, $cipher_data)
    {
        $crypto = new VirgilCrypto();
        $privateKey = $crypto->importPrivateKey(Buffer::fromBase64($private_key));
        $this->assertEquals(
            Buffer::fromBase64($original_data),
            $crypto->decrypt(Buffer::fromBase64($cipher_data), $privateKey)
        );
    }

    /**
     * @dataProvider getSignThenEncryptRecipientsDataProvider
     * @param $private_key
     * @param $signer_private_key
     * @param $original_data
     * @param $cipher_data
     */
    public function testSignThenEncryptRecipient($private_key, $original_data, $cipher_data, $signer_private_key)
    {
        $crypto = new VirgilCrypto();
        $privateKey = $crypto->importPrivateKey(Buffer::fromBase64($private_key));
        $signerPrivateKey = $crypto->importPrivateKey(Buffer::fromBase64($signer_private_key));
        $publicKey = $crypto->extractPublicKey($signerPrivateKey);
        $this->assertEquals(
            Buffer::fromBase64($original_data),
            $crypto->decryptThenVerify(Buffer::fromBase64($cipher_data), $privateKey, $publicKey)
        );
    }

    /**
     * @dataProvider getGenerateSignatureDataProvider
     * @param $private_key
     * @param $original_data
     * @param $signature
     */
    public function testGenerateSignature($private_key, $original_data, $signature)
    {
        $crypto = new VirgilCrypto();
        $privateKey = $crypto->importPrivateKey(Buffer::fromBase64($private_key));
        $this->assertEquals(Buffer::fromBase64($signature), $crypto->sign(Buffer::fromBase64($original_data)->getData(), $privateKey));
    }

    public function getEncryptRecipientsDataProvider()
    {
        return array_merge(
            [$this->getEncryptSingleRecipientData()],
            $this->makeSingleRecipientsFromMultiple($this->getEncryptMultipleRecipients())
        );
    }

    public function getSignThenEncryptRecipientsDataProvider()
    {
        $data = array_merge(
            [$this->getSignThenEncryptSingleRecipientData()],
            $this->makeSingleRecipientsFromMultiple($this->getSignThenEncryptMultipleRecipients())
        );
        $i = 0;
        foreach ($data as &$item) {
            if ($i < 2) {
                $item['signer_private_key'] = $item['private_key'];
            } else {
                $item['signer_private_key'] = $data[1]['private_key'];
            }
            $i++;
        }

        return $data;
    }

    public function getGenerateSignatureDataProvider()
    {
        return [$this->sdkCompatibilityData['generate_signature']];
    }

    private function makeSingleRecipientsFromMultiple($multipleRecipients)
    {
        $data = [];
        foreach ($multipleRecipients['private_keys'] as $private_key) {
            $data[] = ['private_key' => $private_key, 'original_data' => $multipleRecipients['original_data'], 'cipher_data' => $multipleRecipients['cipher_data']];
        }
        return $data;
    }

    private function getEncryptSingleRecipientData()
    {
        return $this->sdkCompatibilityData['encrypt_single_recipient'];
    }

    private function getEncryptMultipleRecipients()
    {
        return $this->sdkCompatibilityData['encrypt_multiple_recipients'];
    }

    private function getSignThenEncryptMultipleRecipients()
    {
        return $this->sdkCompatibilityData['sign_then_encrypt_multiple_recipients'];
    }

    private function getSignThenEncryptSingleRecipientData()
    {
        return $this->sdkCompatibilityData['sign_then_encrypt_single_recipient'];
    }
}