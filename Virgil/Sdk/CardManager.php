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

namespace Virgil\Sdk;


use DateTime;

use Virgil\CryptoApi\CardCrypto;

use Virgil\Sdk\Signer\ModelSigner;

use Virgil\Sdk\Verification\CardVerifier;
use Virgil\Sdk\Verification\NullCardVerifier;

use Virgil\Sdk\Web\ErrorResponseModel;
use Virgil\Sdk\Web\CardClient;
use Virgil\Sdk\Web\RawCardContent;
use Virgil\Sdk\Web\RawSignature;
use Virgil\Sdk\Web\RawSignedModel;

use Virgil\Sdk\Web\Authorization\AccessToken;
use Virgil\Sdk\Web\Authorization\AccessTokenProvider;
use Virgil\Sdk\Web\Authorization\TokenContext;


/**
 * Class CardManager
 * @package Virgil\Sdk
 */
class CardManager
{
    /**
     * @var (RawSignedModel) -> RawSignedModel callable|null
     */
    private $signCallback;
    /**
     * @var ModelSigner
     */
    private $modelSigner;
    /**
     * @var CardCrypto
     */
    private $cardCrypto;
    /**
     * @var AccessTokenProvider
     */
    private $accessTokenProvider;
    /**
     * @var CardVerifier
     */
    private $cardVerifier;
    /**
     * @var CardClient
     */
    private $cardClient;


    public function __construct(
        CardCrypto $cardCrypto,
        AccessTokenProvider $accessTokenProvider,
        CardVerifier $cardVerifier = null,
        CardClient $cardClient = null,
        callable $signCallback = null
    ) {
        if ($cardClient == null) {
            $cardClient = new CardClient();
        }

        if ($cardVerifier == null) {
            $cardVerifier = new NullCardVerifier();
        }

        $this->cardCrypto = $cardCrypto;
        $this->accessTokenProvider = $accessTokenProvider;
        $this->cardClient = $cardClient;
        $this->signCallback = $signCallback;
        $this->modelSigner = new ModelSigner($cardCrypto);
        $this->cardVerifier = $cardVerifier;
    }


    /**
     * @param CardParams $cardParams
     *
     * @return RawSignedModel
     */
    public function generateRawCard(CardParams $cardParams)
    {
        $now = new DateTime();
        $publicKeyString = $this->cardCrypto->exportPublicKey($cardParams->getPublicKey());

        $rawCardContent = new RawCardContent(
            $cardParams->getIdentity(), base64_encode($publicKeyString), '5.0', $now->getTimestamp()
        );
        $rawCardContentSnapshot = json_encode($rawCardContent, JSON_UNESCAPED_SLASHES);

        $rawSignedModel = new RawSignedModel($rawCardContentSnapshot, []);

        try {
            $this->modelSigner->selfSign($rawSignedModel, $cardParams->getPrivateKey(), $cardParams->getExtraFields());
        } catch (VirgilException $e) {
            //model with empty signatures hasn't this exception
        }

        return $rawSignedModel;
    }


    /**
     * @param RawSignedModel $rawSignedModel
     *
     * @return Card
     *
     * @throws CardVerificationException
     * @throws CardClientException
     */
    public function publishRawSignedModel(RawSignedModel $rawSignedModel)
    {
        $contentSnapshot = json_decode($rawSignedModel->getContentSnapshot(), true);

        $tokenContext = new TokenContext($contentSnapshot['identity'], 'publish');
        $token = $this->accessTokenProvider->getToken($tokenContext);

        $card = $this->publishRawSignedModelWithToken($rawSignedModel, $token);
        if (!$this->cardVerifier->verifyCard($card)) {
            throw new CardVerificationException('Validation errors have been detected');
        }

        return $card;
    }


    /**
     * @param CardParams $cardParams
     *
     * @return Card
     *
     * @throws CardVerificationException
     * @throws CardClientException
     */
    public function publishCard(CardParams $cardParams)
    {
        $tokenContext = new TokenContext($cardParams->getIdentity(), 'publish');
        $token = $this->accessTokenProvider->getToken($tokenContext);

        $rawSignedModel = $this->generateRawCard(
            CardParams::create(
                [
                    CardParams::Identity       => $token->identity(),
                    CardParams::PrivateKey     => $cardParams->getPrivateKey(),
                    CardParams::PublicKey      => $cardParams->getPublicKey(),
                    CardParams::ExtraFields    => $cardParams->getExtraFields(),
                    CardParams::PreviousCardID => $cardParams->getPreviousCardID(),
                ]
            )
        );

        $card = $this->publishRawSignedModelWithToken($rawSignedModel, $token);

        if (!$this->cardVerifier->verifyCard($card)) {
            throw new CardVerificationException('Validation errors have been detected');
        }

        return $card;
    }


    /**
     * @param string $cardID
     *
     * @return Card
     *
     * @throws CardClientException
     * @throws CardVerificationException
     */
    public function getCard($cardID)
    {
        $tokenContext = new TokenContext("", 'get');
        $token = $this->accessTokenProvider->getToken($tokenContext);

        $responseModel = $this->cardClient->getCard($cardID, (string)$token);
        if ($responseModel instanceof ErrorResponseModel) {
            throw new CardClientException(
                "error response from card service", $responseModel->getCode(), $responseModel->getMessage()
            );
        }

        $card = $this->parseRawCard($responseModel->getRawSignedModel(), $responseModel->isOutdated());

        if (!$this->cardVerifier->verifyCard($card)) {
            throw new CardVerificationException('Validation errors have been detected');
        }

        return $card;
    }


    /**
     * @param string $identity
     *
     * @return Card[]
     *
     * @throws CardClientException
     * @throws CardVerificationException
     */
    public function searchCards($identity)
    {
        $tokenContext = new TokenContext($identity, 'search');
        $token = $this->accessTokenProvider->getToken($tokenContext);

        $responseModel = $this->cardClient->searchCards($identity, (string)$token);
        if ($responseModel instanceof ErrorResponseModel) {
            throw new CardClientException(
                "error response from card service", $responseModel->getCode(), $responseModel->getMessage()
            );
        }

        $cards = [];
        foreach ($responseModel as $model) {
            $card = $this->parseRawCard($model, false);
            if (!$this->cardVerifier->verifyCard($card)) {
                throw new CardVerificationException('Validation errors have been detected');
            }

            $cards[] = $card;
        }

        return $this->linkCards($cards);
    }


    /**
     * @param string $stringCard
     *
     * @return Card
     *
     * @throws CardVerificationException
     */
    public function importCardFromString($stringCard)
    {
        return $this->importCard(RawSignedModel::RawSignedModelFromBase64String($stringCard));
    }


    /**
     * @param RawSignedModel $rawSignedModel
     *
     * @return Card
     *
     * @throws CardVerificationException
     */
    public function importCard(RawSignedModel $rawSignedModel)
    {
        $card = $this->parseRawCard($rawSignedModel);

        if (!$this->cardVerifier->verifyCard($card)) {
            throw new CardVerificationException('Validation errors have been detected');
        }

        return $card;
    }


    /**
     * @param string $json
     *
     * @return Card
     *
     * @throws CardVerificationException
     */
    public function importCardFromJson($json)
    {
        return $this->importCard(RawSignedModel::RawSignedModelFromJson($json));
    }


    /**
     * @param Card $card
     *
     * @return string
     */
    public function exportCardAsString(Card $card)
    {
        return $this->exportCardAsRawCard($card)
                    ->exportAsBase64String()
            ;
    }


    /**
     * @param Card $card
     *
     * @return string
     */
    public function exportCardAsJson(Card $card)
    {
        return $this->exportCardAsRawCard($card)
                    ->exportAsJson()
            ;
    }


    /**
     * @param Card $card
     *
     * @return RawSignedModel
     */
    public function exportCardAsRawCard(Card $card)
    {
        $modelSignatures = [];
        foreach ($card->getSignatures() as $cardSignature) {
            $modelSignatures[] = new RawSignature(
                $cardSignature->getSigner(), $cardSignature->getSignature(), $cardSignature->getSnapshot()
            );
        }

        return new RawSignedModel($card->getContentSnapshot(), $modelSignatures);
    }


    /**
     * @param RawSignedModel $model
     * @param AccessToken    $token
     *
     * @return Card
     *
     * @throws CardClientException
     */
    private function publishRawSignedModelWithToken(RawSignedModel $model, AccessToken $token)
    {
        if (is_callable($this->signCallback)) {
            $signCallback = $this->signCallback;
            $model = $signCallback($model);
        }

        $responseModel = $this->cardClient->publishCard($model, (string)$token);

        if ($responseModel instanceof ErrorResponseModel) {
            throw new CardClientException(
                "error response from card service", $responseModel->getCode(), $responseModel->getMessage()
            );
        }

        return $this->parseRawCard($responseModel);
    }


    /**
     * @param CardCrypto $cardCrypto
     * @param string     $snapshot
     *
     * @return string
     */
    private function generateCardID(CardCrypto $cardCrypto, $snapshot)
    {
        return bin2hex(substr($cardCrypto->generateSHA512($snapshot), 0, 32));
    }


    /**
     * @param RawSignedModel $rawSignedModel
     *
     * @param bool           $isOutdated
     *
     * @return Card
     */
    private function parseRawCard(RawSignedModel $rawSignedModel, $isOutdated = false)
    {
        $contentSnapshotArray = json_decode($rawSignedModel->getContentSnapshot(), true);

        $cardSignatures = [];
        foreach ($rawSignedModel->getSignatures() as $signature) {
            $extraFields = null;
            if ($signature->getSnapshot() != "") {
                $extraFields = json_decode($signature->getSnapshot(), true);
            }

            $cardSignatures[] = new CardSignature(
                $signature->getSigner(), $signature->getSignature(), $signature->getSnapshot(), $extraFields
            );
        }

        $publicKey = $this->cardCrypto->importPublicKey(base64_decode($contentSnapshotArray['public_key']));

        $previousCardID = null;
        if (array_key_exists('previous_card_id', $contentSnapshotArray)) {
            $previousCardID = $contentSnapshotArray['previous_card_id'];
        }

        return new Card(
            $this->generateCardID($this->cardCrypto, $rawSignedModel->getContentSnapshot()),
            $contentSnapshotArray['identity'],
            $publicKey,
            $contentSnapshotArray['version'],
            (new DateTime())->setTimestamp($contentSnapshotArray['created_at']),
            $isOutdated,
            $cardSignatures,
            $rawSignedModel->getContentSnapshot(),
            $previousCardID
        );
    }


    /**
     * @param Card[] $cards
     *
     * @return Card[]
     */
    private function linkCards(array $cards)
    {
        /** @var Card[] $linkedCards */
        $linkedCards = [];
        foreach ($cards as $card) {
            foreach ($cards as $previousCard) {
                if ($card->getPreviousCardId() == $previousCard->getID()) {
                    $linkedCards[] = new Card(
                        $card->getID(),
                        $card->getIdentity(),
                        $card->getPublicKey(),
                        $card->getVersion(),
                        $card->getCreatedAt(),
                        $card->isOutdated(),
                        $card->getSignatures(),
                        $card->getContentSnapshot(),
                        $card->getPreviousCardId(),
                        new Card(
                            $previousCard->getID(),
                            $previousCard->getIdentity(),
                            $previousCard->getPublicKey(),
                            $previousCard->getVersion(),
                            $previousCard->getCreatedAt(),
                            true,
                            $previousCard->getSignatures(),
                            $previousCard->getContentSnapshot()
                        )
                    );
                }
            }
        }

        foreach ($cards as $card) {
            $isCardAdded = false;
            foreach ($linkedCards as $linkedCard) {
                if ($linkedCard->getID() == $card->getID()) {
                    $isCardAdded = true;
                }

                $previousCard = $linkedCard->getPreviousCard();
                if ($previousCard != null && $previousCard->getID() == $card->getID()) {
                    $isCardAdded = true;
                }
            }
            if (!$isCardAdded) {
                $linkedCards[] = $card;
            }
        }

        return $linkedCards;
    }
}
