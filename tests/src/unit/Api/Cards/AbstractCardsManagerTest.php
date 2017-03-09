<?php
namespace Virgil\Sdk\Tests\Unit\Api\Cards;


use PHPUnit_Framework_MockObject_MockObject;

use Virgil\Sdk\Api\Cards\CardsManager;

use Virgil\Sdk\Api\Cards\CardsManagerInterface;
use Virgil\Sdk\Api\CredentialsInterface;

use Virgil\Sdk\Client\Card;
use Virgil\Sdk\Client\Card\CardMapperInterface;
use Virgil\Sdk\Client\Card\CardSerializerInterface;

use Virgil\Sdk\Client\Requests\RequestSignerInterface;

use Virgil\Sdk\Client\Validator\CardValidatorInterface;

use Virgil\Sdk\Client\VirgilClientInterface;

use Virgil\Sdk\Contracts\CryptoInterface;

use Virgil\Sdk\Tests\BaseTestCase;

class AbstractCardsManagerTest extends BaseTestCase
{
    /** @var CardsManagerInterface */
    protected $cardsManager;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $virgilClient;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $requestSigner;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $cardValidator;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $crypto;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $credentials;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $cardSerializer;

    /** @var PHPUnit_Framework_MockObject_MockObject */
    protected $cardMapper;


    public function setUp()
    {

        $this->virgilClient = $this->createVirgilClient();
        $this->requestSigner = $this->createRequestSigner();
        $this->cardValidator = $this->createCardValidator();
        $this->crypto = $this->createCrypto();
        $this->credentials = $this->createCredentials();
        $this->cardSerializer = $this->createCardSerializer();
        $this->cardMapper = $this->createCardMapper();

        $this->cardsManager = $this->getCardsManager(
            $this->virgilClient,
            $this->requestSigner,
            $this->cardValidator,
            $this->crypto,
            $this->credentials,
            $this->cardSerializer,
            $this->cardMapper
        );
    }


    /**
     * @param VirgilClientInterface   $virgilClient
     * @param RequestSignerInterface  $requestSigner
     * @param CardValidatorInterface  $cardValidator
     * @param CryptoInterface         $crypto
     * @param CredentialsInterface    $credentials
     * @param CardSerializerInterface $cardSerializer
     * @param CardMapperInterface     $cardMapper
     *
     * @return CardsManager
     */
    protected function getCardsManager(
        VirgilClientInterface $virgilClient,
        RequestSignerInterface $requestSigner,
        CardValidatorInterface $cardValidator,
        CryptoInterface $crypto,
        CredentialsInterface $credentials,
        CardSerializerInterface $cardSerializer,
        CardMapperInterface $cardMapper
    ) {
        return new CardsManager(
            $virgilClient, $requestSigner, $cardValidator, $crypto, $credentials, $cardSerializer, $cardMapper
        );
    }


    /**
     * @return CardSerializerInterface
     */
    protected function createCardSerializer()
    {
        return $this->createMock(CardSerializerInterface::class);
    }


    /**
     * @return VirgilClientInterface
     */
    protected function createVirgilClient()
    {
        return $this->createMock(VirgilClientInterface::class);
    }


    /**
     * @return RequestSignerInterface
     */
    protected function createRequestSigner()
    {
        return $this->createMock(RequestSignerInterface::class);
    }


    /**
     * @return CardValidatorInterface
     */
    protected function createCardValidator()
    {
        return $this->createMock(CardValidatorInterface::class);
    }


    /**
     * @return CryptoInterface
     */
    protected function createCrypto()
    {
        return $this->createMock(CryptoInterface::class);
    }


    /**
     * @return CredentialsInterface
     */
    protected function createCredentials()
    {
        return $this->createMock(CredentialsInterface::class);
    }


    /**
     * @return CardMapperInterface
     */
    protected function createCardMapper()
    {
        return $this->createMock(CardMapperInterface::class);
    }


    /**
     *
     * @return Card
     */
    protected function createCard()
    {
        return $this->createMock(Card::class);
    }
}
