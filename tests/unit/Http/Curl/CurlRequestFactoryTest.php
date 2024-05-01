<?php
/**
 * Copyright (C) 2015-2020 Virgil Security Inc.
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 *     (1) Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *     (2) Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *     (3) Neither the name of the copyright holder nor the names of its
 *     contributors may be used to endorse or promote products derived from
 *     this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ''AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING
 * IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Lead Maintainer: Virgil Security Inc. <support@virgilsecurity.com>
 */

namespace Tests\Unit\Http\Curl;

use Virgil\Sdk\Http\Curl\CurlRequestFactory;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CurlRequestFactoryTest extends TestCase
{

    #[Test]
    public function create__requestWithSameOptions__returnsUniqueCurlRequests()
    {
        $factory = new CurlRequestFactory();


        $request = $factory->create([CURLOPT_URL => '/card']);
        $newRequest = $factory->create([CURLOPT_URL => '/card']);


        $this->assertNotSame($request, $newRequest);
    }


    #[Test]
    public function create__withDefaultOptions__returnsRequestMergedOptions()
    {
        $expectedOptions = [
            CURLOPT_RETURNTRANSFER => 0,
            CURLOPT_HTTPHEADER => ['qwe: rty', 'Host: http://qwerty.com/'],
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POST => 1,
            CURLOPT_URL => '/card',
            CURLOPT_POSTFIELDS => ['alice' => 'bob'],
        ];

        $factory = new CurlRequestFactory();
        $factory->setDefaultOptions(
            [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER => ['Host: http://qwerty.com/'],
            ]
        );


        $request = $factory->create(
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POST => 1,
                CURLOPT_URL => '/card',
                CURLOPT_POSTFIELDS => ['alice' => 'bob'],
                CURLOPT_RETURNTRANSFER => 0,
                CURLOPT_HTTPHEADER => ['qwe: rty', 'Host: http://qwerty.com/'],
            ]
        );


        $this->assertEquals($expectedOptions, $request->getOptions());
    }
}
