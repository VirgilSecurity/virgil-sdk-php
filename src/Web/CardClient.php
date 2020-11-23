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

declare(strict_types=1);

namespace Virgil\Sdk\Web;

use Virgil\Sdk\Http\HttpClientInterface;
use Virgil\Sdk\Http\Curl\CurlClient;
use Virgil\Sdk\Http\Curl\CurlRequestFactory;
use Virgil\Sdk\Http\Requests\GetHttpRequest;
use Virgil\Sdk\Http\Requests\PostHttpRequest;
use Virgil\Sdk\Http\VirgilAgent\HttpVirgilAgent;


/**
 * Class CardClient
 * @package Virgil\Sdk\Web
 */
class CardClient
{
    const API_SERVICE_URL = 'https://api.virgilsecurity.com';

    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var string
     */
    private $serviceUrl;

    /**
     * @var HttpVirgilAgent
     */
    private $httpVirgilAgent;

    /**
     * CardClient constructor.
     * @param HttpVirgilAgent $httpVirgilAgent
     * @param string $serviceUrl
     * @param HttpClientInterface|null $httpClient
     */
    public function __construct(
        HttpVirgilAgent $httpVirgilAgent,
        $serviceUrl = self::API_SERVICE_URL,
        HttpClientInterface $httpClient = null
    ) {
        $this->httpVirgilAgent = $httpVirgilAgent;
        if ($httpClient == null) {
            $httpClient = new CurlClient(
                new CurlRequestFactory(
                    [
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_HEADER => true,
                    ]
                )
            );
        }
        $this->httpClient = $httpClient;
        $this->serviceUrl = rtrim($serviceUrl, "/");
    }

    /**
     * @param RawSignedModel $model
     * @param $token
     * @return ErrorResponseModel|RawSignedModel
     */
    public function publishCard(RawSignedModel $model, $token)
    {
        $httpResponse = $this->httpClient->send(
            new PostHttpRequest(
                $this->serviceUrl . "/card/v5",
                json_encode($model, JSON_UNESCAPED_SLASHES),
                [
                    "Authorization" => sprintf("Virgil %s", $token),
                    $this->httpVirgilAgent->getName() => $this->httpVirgilAgent->getFormatString(),
                ]
            )
        );

        if (!$httpResponse->getHttpStatusCode()
                          ->isSuccess()) {
            return $this->parseErrorResponse($httpResponse->getBody());
        }

        return RawSignedModel::RawSignedModelFromJson($httpResponse->getBody());
    }

    /**
     * @param $cardID
     * @param $token
     * @return ErrorResponseModel|ResponseModel
     */
    public function getCard($cardID, $token)
    {
        $httpResponse = $this->httpClient->send(
            new GetHttpRequest(
                sprintf("%s/card/v5/%s", $this->serviceUrl, $cardID),
                null,
                [
                    "Authorization" => sprintf("Virgil %s", $token),
                    $this->httpVirgilAgent->getName() => $this->httpVirgilAgent->getFormatString(),
                ]
            )
        );

        if (!$httpResponse->getHttpStatusCode()
                          ->isSuccess()) {
            return $this->parseErrorResponse($httpResponse->getBody());
        }

        $rawSignedModel = RawSignedModel::RawSignedModelFromJson($httpResponse->getBody());

        return new ResponseModel($httpResponse->getHeaders(), $rawSignedModel);
    }

    /**
     * @param $identity
     * @param $token
     * @return array|ErrorResponseModel
     */
    public function searchCards($identity, $token)
    {
        $httpResponse = $this->httpClient->send(
            new PostHttpRequest(
                $this->serviceUrl . "/card/v5/actions/search",
                json_encode(["identity" => $identity], JSON_UNESCAPED_SLASHES),
                [
                    "Authorization" => sprintf("Virgil %s", $token),
                    $this->httpVirgilAgent->getName() => $this->httpVirgilAgent->getFormatString(),
                ]
            )
        );

        if (!$httpResponse->getHttpStatusCode()
                          ->isSuccess()) {
            return $this->parseErrorResponse($httpResponse->getBody());
        }

        $rawModels = [];

        $rawModelsJson = json_decode($httpResponse->getBody(), true);
        foreach ($rawModelsJson as $rawModelJson) {
            $rawModels[] = RawSignedModel::RawSignedModelFromJson(json_encode($rawModelJson, JSON_UNESCAPED_SLASHES));
        }

        return $rawModels;
    }

    /**
     * @param $cardID
     * @param $token
     * @return ErrorResponseModel|null
     */
    public function revokeCard($cardID, $token)
    {
        $httpResponse = $this->httpClient->send(
            new PostHttpRequest(
                sprintf("%s/card/v5/actions/revoke/%s", $this->serviceUrl, $cardID),
                null,
                [
                    "Authorization" => sprintf("Virgil %s", $token),
                    $this->httpVirgilAgent->getName() => $this->httpVirgilAgent->getFormatString(),
                ]
            )
        );

        if (!$httpResponse->getHttpStatusCode()
            ->isSuccess()) {
            return $this->parseErrorResponse($httpResponse->getBody());
        }

        return null;
    }

    private function parseErrorResponse(string $errorBody): ErrorResponseModel
    {
        $code = 20000;
        $message = "error during request serving";
        $badResponseBody = json_decode($errorBody, true);

        if (is_array($badResponseBody)) {
            if (array_key_exists('code', $badResponseBody)) {
                $code = $badResponseBody['code'];
            }
            if (array_key_exists('message', $badResponseBody)) {
                $message = $badResponseBody['message'];
            }
        }

        return new ErrorResponseModel((int)$code, (string)$message);
    }

}
