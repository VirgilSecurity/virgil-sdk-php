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

namespace Virgil\Sdk\Web;


use Virgil\Http\HttpClientInterface;

use Virgil\Http\Requests\GetHttpRequest;
use Virgil\Http\Requests\PostHttpRequest;


/**
 * Class CardClient
 * @package Virgil\Sdk\Web
 */
class CardClient
{

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;
    /**
     * @var string
     */
    private $serviceUrl;


    /**
     * CardClient constructor.
     *
     * @param string              $serviceUrl
     * @param HttpClientInterface $httpClient
     */
    public function __construct($serviceUrl, HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->serviceUrl = rtrim($serviceUrl, "/");
    }


    /**
     * @param RawSignedModel $model
     * @param string         $token
     *
     * @return RawSignedModel|ErrorResponseModel
     *
     */
    public function publishCard(RawSignedModel $model, $token)
    {
        $httpResponse = $this->httpClient->send(
            new PostHttpRequest(
                $this->serviceUrl . "/card/v5",
                json_encode($model, JSON_UNESCAPED_SLASHES),
                ["Authorization" => sprintf("Virgil %s", $token)]
            )
        );

        if (!$httpResponse->getHttpStatusCode()
                          ->isSuccess()) {
            return $this->parseErrorResponse($httpResponse->getBody());
        }

        return RawSignedModel::RawSignedModelFromJson($httpResponse->getBody());
    }


    /**
     * @param string $cardID
     * @param string $token
     *
     * @return ResponseModel|ErrorResponseModel
     *
     */
    public function getCard($cardID, $token)
    {
        $httpResponse = $this->httpClient->send(
            new GetHttpRequest(
                sprintf("%s/card/v5/%s", $this->serviceUrl, $cardID),
                null,
                ["Authorization" => sprintf("Virgil %s", $token)]
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
     * @param string $identity
     * @param string $token
     *
     * @return RawSignedModel[]|ErrorResponseModel
     */
    public function searchCards($identity, $token)
    {
        $httpResponse = $this->httpClient->send(
            new PostHttpRequest(
                $this->serviceUrl . "/card/v5/actions/search",
                json_encode(["identity" => $identity], JSON_UNESCAPED_SLASHES),
                ["Authorization" => sprintf("Virgil %s", $token)]
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
     * @param string $errorBody
     *
     * @return ErrorResponseModel
     */
    protected function parseErrorResponse($errorBody)
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

        return new ErrorResponseModel($code, $message);
    }

}
