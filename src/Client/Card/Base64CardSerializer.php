<?php
namespace Virgil\Sdk\Client\Card;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Card;

use Virgil\Sdk\Client\VirgilServices\Mapper\CardContentModelMapper;
use Virgil\Sdk\Client\VirgilServices\Mapper\SignedResponseModelMapper;

/**
 * Class serializes and unserializes base64 encoded card.
 */
class Base64CardSerializer implements CardSerializerInterface
{
    /** @var SignedResponseModelMapper */
    private $signedResponseModelMapper;

    /** @var SignedResponseCardMapper */
    private $signedResponseCardMapper;


    /**
     * Class constructor.
     *
     * @param SignedResponseCardMapper  $signedResponseCardMapper
     * @param SignedResponseModelMapper $signedResponseModelMapper
     */
    public function __construct(
        SignedResponseCardMapper $signedResponseCardMapper,
        SignedResponseModelMapper $signedResponseModelMapper
    ) {
        $this->signedResponseCardMapper = $signedResponseCardMapper;
        $this->signedResponseModelMapper = $signedResponseModelMapper;
    }


    /**
     * Creates default base64 card serializer.
     *
     * @return Base64CardSerializer
     */
    public static function create()
    {
        $signedResponseModelMapper = new SignedResponseModelMapper(new CardContentModelMapper());
        $signedResponseCardMapper = new SignedResponseCardMapper();

        return new self($signedResponseCardMapper, $signedResponseModelMapper);
    }


    /**
     * Unserializes base64 encoded string to card.
     *
     * @param string $serialized base64 encoded string.
     *
     * @return Card
     */
    public function unserialize($serialized)
    {
        $signedResponseJsonBuffer = Buffer::fromBase64($serialized);

        $signedResponseModel = $this->signedResponseModelMapper->toModel((string)$signedResponseJsonBuffer);

        return $this->signedResponseCardMapper->toCard($signedResponseModel);
    }


    /**
     * Serializes card to base64 encoded string.
     *
     * @param Card $card
     *
     * @return string base64 encoded string.
     */
    public function serialize(Card $card)
    {
        $signedResponseModel = $this->signedResponseCardMapper->toModel($card);

        $signedResponseJson = $this->signedResponseModelMapper->toJson($signedResponseModel);

        return (new Buffer($signedResponseJson))->toBase64();
    }
}
