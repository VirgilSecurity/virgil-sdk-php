<?php

namespace Tests\Virgil\Http\Curl;


use Virgil\Http\Curl\CurlRequestFactory;
use PHPUnit\Framework\TestCase;

class CurlRequestFactoryTest extends TestCase
{

    /**
     * @test
     */
    public function create__requestWithSameOptions__returnsUniqueCurlRequests()
    {
        $factory = new CurlRequestFactory();


        $request = $factory->create([CURLOPT_URL => '/card']);
        $newRequest = $factory->create([CURLOPT_URL => '/card']);


        $this->assertNotSame($request, $newRequest);
        //same options but different curl handlers
        $this->assertNotEquals($request, $newRequest);
    }


    /**
     * @test
     */
    public function create__withDefaultOptions__returnsRequestMergedOptions()
    {
        $expectedOptions = [
            CURLOPT_RETURNTRANSFER => 0,
            CURLOPT_HTTPHEADER     => ['qwe: rty', 'Host: http://qwerty.com/'],
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POST           => 1,
            CURLOPT_URL            => '/card',
            CURLOPT_POSTFIELDS     => ['alice' => 'bob'],
        ];

        $factory = new CurlRequestFactory();
        $factory->setDefaultOptions(
            [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER     => ['Host: http://qwerty.com/'],
            ]
        );


        $request = $factory->create(
            [
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POST           => 1,
                CURLOPT_URL            => '/card',
                CURLOPT_POSTFIELDS     => ['alice' => 'bob'],
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HTTPHEADER     => ['qwe: rty', 'Host: http://qwerty.com/'],
            ]
        );


        $this->assertEquals($expectedOptions, $request->getOptions());
    }
}
