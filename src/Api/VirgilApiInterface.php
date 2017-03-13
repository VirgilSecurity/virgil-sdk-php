<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Api\Cards\CardsManagerInterface;

use Virgil\Sdk\Api\Keys\KeysManagerInterface;

use Virgil\Sdk\Client\Card\CardMapperInterface;
use Virgil\Sdk\Client\Card\CardSerializerInterface;

use Virgil\Sdk\Client\Requests\RequestSignerInterface;

use Virgil\Sdk\Client\Validator\CardValidatorInterface;

use Virgil\Sdk\Client\VirgilClientInterface;

/**
 * The a virgil api interface defines a high-level API that provides easy access to Virgil Security services and allows
 * to perform cryptographic operations by using two domain entities.
 */
interface VirgilApiInterface
{
    /**
     * Creates a virgil api from access token only.
     *
     * @param string $accessToken
     *
     * @return $this
     */
    public static function create($accessToken = null);


    /**
     * Gets a Keys Manager.
     *
     * @return KeysManagerInterface
     */
    public function getKeys();


    /**
     * Gets a Cards Manager.
     *
     * @return CardsManagerInterface
     */
    public function getCards();


    /**
     * Sets a custom virgil client.
     *
     * @param VirgilClientInterface $virgilClient
     *
     * @return $this
     */
    public function setClient(VirgilClientInterface $virgilClient);


    /**
     * Gets a virgil client.
     *
     * @return VirgilClientInterface
     */
    public function getClient();


    /**
     * Sets a custom request signer.
     *
     * @param RequestSignerInterface $requestSigner
     *
     * @return $this
     */
    public function setRequestSigner(RequestSignerInterface $requestSigner);


    /**
     * Gets a request signer.
     *
     * @return RequestSignerInterface
     */
    public function getRequestSigner();


    /**
     * Sets a custom card validator.
     *
     * @param CardValidatorInterface $cardValidator
     *
     * @return $this
     */
    public function setCardValidator(CardValidatorInterface $cardValidator);


    /**
     * Gets a card validator.
     *
     * @return CardValidatorInterface
     */
    public function getCardValidator();


    /**
     * Sets a custom card serializer.
     *
     * @param CardSerializerInterface $cardSerializer
     *
     * @return $this
     */
    public function setCardSerializer(CardSerializerInterface $cardSerializer);


    /**
     * Gets a custom card serializer.
     *
     * @return CardSerializerInterface
     */
    public function getCardSerializer();


    /**
     * Sets a custom card mapper.
     *
     * @param CardMapperInterface $cardMapper
     *
     * @return $this
     */
    public function setCardsMapper(CardMapperInterface $cardMapper);


    /**
     * Gets a card mapper.
     *
     * @return CardMapperInterface
     */
    public function getCardsMapper();
}
