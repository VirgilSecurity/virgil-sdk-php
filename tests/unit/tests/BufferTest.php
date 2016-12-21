<?php
namespace Virgil\Tests\Unit\Cryptography;


use PHPUnit\Framework\TestCase;

use Virgil\Sdk\Buffer;

class BufferTest extends TestCase
{
    public function testBase64EncodeDecode()
    {
        $originalData = 'string_to_encode';
        $buffer = new Buffer($originalData);
        $this->assertEquals($originalData, Buffer::fromBase64($buffer->toBase64())->getData());
    }

    public function testHexEncodeDecode()
    {
        $originalData = 'string_to_encode';
        $buffer = new Buffer($originalData);
        $this->assertEquals($originalData, Buffer::fromHex($buffer->toHex())->getData());
    }

    public function testGetData()
    {
        $originalData = 'string_to_encode';
        $buffer = new Buffer($originalData);
        $this->assertEquals($originalData, $buffer->getData());
    }

    public function testToString()
    {
        $originalData = 'string_to_encode';
        $buffer = new Buffer($originalData);
        $this->assertEquals($originalData, $buffer->toString());
    }

    public function testFromBase64()
    {
        $originalData = 'string_to_encode';
        $buffer = new Buffer($originalData);
        $base64String = $buffer->toBase64();
        $this->assertEquals($buffer, Buffer::fromBase64($base64String));
    }

    public function testFromHex()
    {
        $originalData = 'string_to_encode';
        $buffer = new Buffer($originalData);
        $base64String = $buffer->toHex();
        $this->assertEquals($buffer, Buffer::fromHex($base64String));
    }
}
