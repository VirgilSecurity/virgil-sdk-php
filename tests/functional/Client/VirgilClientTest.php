<?php

namespace Virgil\Tests\Functional\Client;


use PHPUnit\Framework\TestCase;
use Virgil\SDK\Buffer;
use Virgil\SDK\BufferInterface;
use Virgil\SDK\Client\Card;
use Virgil\SDK\Client\Card\CardsServiceException;
use Virgil\SDK\Client\Card\Model\SearchCriteria;
use Virgil\SDK\Client\CardScope;
use Virgil\SDK\Client\CreateCardRequest;
use Virgil\SDK\Client\RequestSigner;
use Virgil\SDK\Client\RevocationReason;
use Virgil\SDK\Client\RevokeCardRequest;
use Virgil\SDK\Client\VirgilClient;
use Virgil\SDK\Client\VirgilClientParams;
use Virgil\SDK\Cryptography\VirgilCrypto;

class VirgilClientTest extends TestCase
{
    private $applicationSettings;
    private $virgilClient;
    private $requestSigner;
    private $crypto;
    private static $cardsData = [];

    public function __construct($name, $data = [], $dataName)
    {
        parent::__construct($name, $data, $dataName);

        $settings = json_decode(file_get_contents(VIRGIL_TEST_CONFIG_PATH), true);
        $this->applicationSettings = $settings['application_settings'];

        $params = new VirgilClientParams($this->applicationSettings['access_token']);
        $params->setCardsServiceAddress('https://cards-stg.virgilsecurity.com');
        $params->setReadOnlyCardsServiceAddress('https://cards-ro-stg.virgilsecurity.com');
        $params->setIdentityServiceAddress('https://identity-stg.virgilsecurity.com');
        $this->virgilClient = new VirgilClient($params);

        $this->crypto = $crypto = new VirgilCrypto();
        $this->requestSigner = new RequestSigner($crypto);
    }

    /**
     * @dataProvider cardsDataProvider
     * @param $identity
     * @param $identityType
     * @param $scope
     * @param BufferInterface $publicKey
     * @param BufferInterface $privateKey
     */
    public function testCreateCard($identity, $identityType, $scope, BufferInterface $publicKey, BufferInterface $privateKey)
    {
        $request = new CreateCardRequest($identity, $identityType, $publicKey, $scope);
        $expectedId = $this->crypto->calculateFingerprint(Buffer::fromBase64($request->snapshot()))->toHex();

        $this->requestSigner->selfSign($request, $this->crypto->importPrivateKey($privateKey));
        $this->requestSigner->authoritySign($request, $this->applicationSettings['id'], $this->crypto->importPrivateKey(
            Buffer::fromBase64($this->applicationSettings['private_key']),
            $this->applicationSettings['password']
        ));

        $card = $this->virgilClient->createCard($request);

        $this->assertEquals($expectedId, $card->getId());
        $this->assertInstanceOf(Card::class, $card);
    }

    /**
     * @dataProvider cardsDataProvider
     * @param $identity
     * @param $identityType
     * @param $scope
     * @param BufferInterface $publicKey
     * @param BufferInterface $privateKey
     */
    public function testCreateCardFailOnBadSelfSign($identity, $identityType, $scope, BufferInterface $publicKey, BufferInterface $privateKey)
    {
        $request = new CreateCardRequest($identity, $identityType, $publicKey, $scope);
        $expectedId = $this->crypto->calculateFingerprint(Buffer::fromBase64($request->snapshot()))->toHex();

        $keys = $this->crypto->generateKeys();

        $this->requestSigner->selfSign($request, $this->crypto->importPrivateKey($keys->getPrivateKey()->getValue()));

        try {
            $this->virgilClient->createCard($request);
        } catch (CardsServiceException $exception) {
            $this->assertEquals('400', $exception->getCode());
            $this->assertContains('SCR sign item signed digest is invalid for the application', $exception->getMessage());
        }
    }


    public function testSearchCard()
    {
        $searchCriteria = new SearchCriteria(
            array_map(function ($card) {
                return $card[0];
            }, self::$cardsData), self::$cardsData[0][1], self::$cardsData[0][2]
        );

        $cards = $this->virgilClient->searchCard($searchCriteria);

        $this->assertEquals(count($searchCriteria->getIdentities()), count($cards));
        foreach ($cards as $card) {
            $this->assertInstanceOf(Card::class, $card);
        }
    }

    /**
     * @dataProvider cardsDataProvider
     * @param $identity
     * @param $identityType
     * @param $scope
     * @param BufferInterface $publicKey
     * @param BufferInterface $privateKey
     */
    public function testGetCard($identity, $identityType, $scope, BufferInterface $publicKey, BufferInterface $privateKey)
    {
        $request = new CreateCardRequest($identity, $identityType, $publicKey, $scope);
        $expectedId = $this->crypto->calculateFingerprint(Buffer::fromBase64($request->snapshot()))->toHex();
        $card = $this->virgilClient->getCard($expectedId);
        $this->assertEquals($expectedId, $card->getId());
        $this->assertInstanceOf(Card::class, $card);
    }

    /**
     * @dataProvider  cardsDataProvider
     * @param $identity
     * @param $identityType
     * @param $scope
     * @param BufferInterface $publicKey
     * @param BufferInterface $privateKey
     */
    public function testRevokeCard($identity, $identityType, $scope, BufferInterface $publicKey, BufferInterface $privateKey)
    {
        $request = new CreateCardRequest($identity, $identityType, $publicKey, $scope);
        $expectedId = $this->crypto->calculateFingerprint(Buffer::fromBase64($request->snapshot()))->toHex();

        $card = $this->virgilClient->getCard($expectedId);

        $this->assertInstanceOf(Card::class, $card);
        $this->assertEquals($expectedId, $card->getId());

        $revokeRequest = new RevokeCardRequest($card->getId(), RevocationReason::UNSPECIFIED_TYPE);

        $this->requestSigner->selfSign($revokeRequest, $this->crypto->importPrivateKey($privateKey));
        $this->requestSigner->authoritySign($revokeRequest, $this->applicationSettings['id'], $this->crypto->importPrivateKey(
            Buffer::fromBase64($this->applicationSettings['private_key']),
            $this->applicationSettings['password']
        ));

        $this->virgilClient->revokeCard($revokeRequest);

        try {
            $this->virgilClient->getCard($expectedId);
        } catch (CardsServiceException $exception) {
            $this->assertEquals('404', $exception->getCode());
            $this->assertContains('Entity not found', $exception->getMessage());
        }

        $cards = $this->virgilClient->searchCard(new SearchCriteria(
            [$identity], $identityType, $scope
        ));

        $this->assertEmpty($cards);
    }

    public function cardsDataProvider()
    {
        if (count(self::$cardsData) == 0) {
            $identity = baseIdentityGenerator('ykuzichtest');
            $identityType = 'phpsdktest';
            $scope = CardScope::TYPE_APPLICATION;
            $crypto = new VirgilCrypto();

            $cardData = function () use ($identity, $identityType, $scope, $crypto) {
                $keys = $crypto->generateKeys();
                return [$identity(), $identityType, $scope, $keys->getPublicKey()->getValue(), $keys->getPrivateKey()->getValue()];
            };

            self::$cardsData = [$cardData(), $cardData(), $cardData()];
        }

        return self::$cardsData;
    }

}

function baseIdentityGenerator($base)
{
    $g = call_user_func(function ($val) {
        while (true) {
            yield $val . uniqid();
        }
    }, $base);

    return function () use ($g) {
        $c = $g->current();
        $g->next();
        return $c;
    };
}