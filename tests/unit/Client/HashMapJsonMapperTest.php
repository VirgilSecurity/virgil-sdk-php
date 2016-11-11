<?php
namespace Virgil\Tests\Unit\Client;

use PHPUnit\Framework\TestCase;
use Virgil\SDK\Client\HashMapJsonMapper;

class HashMapJsonMapperTest extends TestCase
{

    public function testToModel()
    {
        $json = '{"id":"id-1", "name":"qwerty", "items":[{"id":"1"},{"id":"2"}]}';
        $expected = [
            'id' => 'id-1',
            'name' => 'qwerty',
            'items' => [
                ['id' => '1'],
                ['id' => '2']
            ]
        ];
        $mapper = new HashMapJsonMapper();
        $this->assertEquals($expected, $mapper->toModel($json));
    }

    public function testToJson()
    {
        $json = '{"id":"id-1","name":"qwerty","items":[{"id":"1"},{"id":"2"}]}';
        $expected = [
            'id' => 'id-1',
            'name' => 'qwerty',
            'items' => [
                ['id' => '1'],
                ['id' => '2']
            ]
        ];
        $mapper = new HashMapJsonMapper();
        $this->assertEquals($json, $mapper->toJson($expected));
    }
}