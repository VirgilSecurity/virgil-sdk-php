<?php
namespace Virgil\Sdk\Tests\Unit;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Buffer;

class BufferTest extends TestCase
{
    /**
     * @test
     */
    public function toBase64__fromString__returnsBase64EncodedString()
    {
        $expectedBase64EncodedString = 'c3RyaW5nX3RvX2VuY29kZQ==';
        $originalData = 'string_to_encode';

        $buffer = new Buffer($originalData);


        $base64EncodedString = $buffer->toBase64();


        $this->assertEquals($expectedBase64EncodedString, $base64EncodedString);
    }


    /**
     * @test
     */
    public function fromBase64__base64EncodedString__returnsDecodedString()
    {
        $expectedString = 'string_to_encode';
        $base64EncodedString = 'c3RyaW5nX3RvX2VuY29kZQ==';

        $buffer = Buffer::fromBase64($base64EncodedString);


        $originalData = $buffer->getData();


        $this->assertEquals($expectedString, $originalData);
    }


    /**
     * @test
     */
    public function toHex__fromString__returnsHexadecimalString()
    {
        $originalData = 'string_to_encode';
        $expectedHexadecimalString = '737472696e675f746f5f656e636f6465';

        $buffer = new Buffer($originalData);


        $hexadecimalString = $buffer->toHex();


        $this->assertEquals($expectedHexadecimalString, $hexadecimalString);
    }


    /**
     * @test
     */
    public function fromHex__hexadecimalString__returnsDecodedString()
    {
        $expectedOriginalData = 'string_to_encode';
        $hexadecimalString = '737472696e675f746f5f656e636f6465';

        $buffer = Buffer::fromHex($hexadecimalString);


        $originalData = $buffer->getData();


        $this->assertEquals($expectedOriginalData, $originalData);
    }


    /**
     * @test
     */
    public function fromBase64__withBase64EncodedString__createsValidBase64Buffer()
    {
        $originalData = 'string_to_encode';
        $base64String = 'c3RyaW5nX3RvX2VuY29kZQ==';

        $expectedBuffer = new Buffer($originalData);


        $base64Buffer = Buffer::fromBase64($base64String);


        $this->assertEquals($expectedBuffer, $base64Buffer);
    }


    /**
     * @test
     */
    public function fromHex__withHexadecimalString__createsValidHexBuffer()
    {
        $originalData = 'string_to_encode';
        $hexadecimalString = '737472696e675f746f5f656e636f6465';

        $expectedBuffer = new Buffer($originalData);


        $hexadecimalBuffer = Buffer::fromHex($hexadecimalString);


        $this->assertEquals($expectedBuffer, $hexadecimalBuffer);
    }
}
