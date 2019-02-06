<?php
/**
 * Copyright (C) 2015-2018 Virgil Security Inc.
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

namespace Virgil\Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Virgil\CryptoApi\CardCrypto;
use Virgil\CryptoImpl\VirgilAccessTokenSigner;
use Virgil\CryptoImpl\VirgilCardCrypto;
use Virgil\CryptoImpl\VirgilCrypto;
use Virgil\Sdk\CardManager;
use Virgil\Sdk\Verification\CardVerifier;
use Virgil\Sdk\Verification\VirgilCardVerifier;
use Virgil\Sdk\Web\Authorization\AccessTokenProvider;
use Virgil\Sdk\Web\Authorization\GeneratorJwtProvider;
use Virgil\Sdk\Web\Authorization\JwtGenerator;
use Virgil\Sdk\Web\CardClient;

/**
 * Class BaseIntegrationTestCase
 * @package Virgil\Tests
 */
class IntegrationBaseTestCase extends TestCase
{
    /**
     * @var IntegrationTestsDataProvider
     */
    protected $fixtures;
    /**
     * @var string
     */
    protected $serviceAddress;
    /**
     * @var
     */
    protected $serviceKey;
    /**
     * @var
     */
    protected $testApiKeyId;
    /**
     * @var
     */
    protected $testApiKey;
    /**
     * @var
     */
    protected $testAppId;
    /**
     * @var CardCrypto
     */
    protected $cardCrypto;
    /**
     * @var AccessTokenProvider
     */
    protected $accessTokenProvider;
    /**
     * @var CardVerifier
     */
    protected $cardVerifier;
    /**
     * @var CardClient
     */
    protected $cardClient;
    /**
     * @var callable|null
     */
    protected $signCallback = null;
    /**
     * @var VirgilCrypto
     */
    protected $virgilCrypto;


    public function setUp()
    {
        parent::setUp();

        (new Dotenv(__DIR__."/../.."))->load();

        defined('VIRGIL_FIXTURE_PATH') or define('VIRGIL_FIXTURE_PATH', __DIR__.'/../fixtures/');

        $this->serviceAddress = $_ENV['SERVICE_ADDRESS'];
        $this->serviceKey = $_ENV['SERVICE_KEY'];
        $this->testApiKeyId = $_ENV['API_KEY_ID'];
        $this->testApiKey = $_ENV['API_KEY'];
        $this->testAppId = $_ENV['APP_ID'];

        $this->fixtures = new IntegrationTestsDataProvider(VIRGIL_FIXTURE_PATH . DIRECTORY_SEPARATOR . "data.json");

        $this->virgilCrypto = new VirgilCrypto();

        $apiKey = $this->virgilCrypto->importPrivateKey(base64_decode($this->testApiKey), '');

        $this->cardCrypto = new VirgilCardCrypto();

        $virgilAccessTokenSigner = new VirgilAccessTokenSigner();
        $this->accessTokenProvider = new GeneratorJwtProvider(
            new JwtGenerator($apiKey, $this->testApiKeyId, $virgilAccessTokenSigner, $this->testAppId, $_ENV['TTL']),
            'default-identity'.date('Y-m-d-H-i-s')
        );
        $this->cardVerifier = new VirgilCardVerifier($this->cardCrypto, true, true, [], $this->serviceKey);
        $this->cardClient = new CardClient($this->serviceAddress);
    }


    protected function getCardManager()
    {
        return new CardManager(
            $this->cardCrypto, $this->accessTokenProvider, $this->cardVerifier, $this->cardClient, $this->signCallback
        );
    }


    protected function baseIdentityGenerator($base)
    {
        $g = call_user_func(
            function ($val) {
                while (true) {
                    yield $val . uniqid();
                }
            },
            $base
        );

        return function () use ($g) {
            $c = $g->current();
            $g->next();

            return $c;
        };
    }
}
