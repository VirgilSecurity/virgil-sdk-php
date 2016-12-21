<?php
namespace Virgil\Tests\Unit\Client\Http\Curl;


use PHPUnit\Framework\TestCase;
use Virgil\Sdk\Client\Http\Curl\CurlRequestFactory;


class CurlRequestFactoryTest extends TestCase
{
    public function testCreatedRequests()
    {
        $factory = new CurlRequestFactory();
        $request = $factory->create([CURLOPT_URL => '/card']);
        $newRequest = $factory->create([CURLOPT_URL => '/card']);
        $this->assertNotEquals($request, $newRequest);
        $request->execute();
        $this->assertEquals('/card', $request->getInfo(CURLINFO_EFFECTIVE_URL));
    }

    public function testRequestDefaultOptionsMergingAndOverriding()
    {
        $expectedOptions = [
            CURLOPT_RETURNTRANSFER => 0,
            CURLOPT_HTTPHEADER => ['qwe: rty', 'Host: http://qwerty.com/'],
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => 1,
            CURLOPT_URL => '/card',
            CURLOPT_POSTFIELDS => ['alice' => 'bob']
        ];

        $factory = new CurlRequestFactory();
        $factory->setDefaultOptions([
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => ['Host: http://qwerty.com/'],
        ]);

        $request = $factory->create([
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => 1,
            CURLOPT_URL => '/card',
            CURLOPT_POSTFIELDS => ['alice' => 'bob'],
            CURLOPT_RETURNTRANSFER => 0,
            CURLOPT_HTTPHEADER => ['qwe: rty', 'Host: http://qwerty.com/']
        ]);

        $this->assertEquals($expectedOptions, $request->getOptions());
    }
}
