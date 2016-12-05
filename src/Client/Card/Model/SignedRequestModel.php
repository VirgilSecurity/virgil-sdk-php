<?php
namespace Virgil\Sdk\Client\Card\Model;


use JsonSerializable;

class SignedRequestModel
{
    protected $cardContent;
    protected $meta;

    /**
     * SignedRequestModel constructor.
     *
     * @param JsonSerializable $cardContent
     * @param JsonSerializable $meta
     */
    public function __construct(JsonSerializable $cardContent, JsonSerializable $meta)
    {
        $this->cardContent = $cardContent;
        $this->meta = $meta;
    }

    /**
     * @return JsonSerializable
     */
    public function getCardContent()
    {
        return $this->cardContent;
    }

    /**
     * @return JsonSerializable
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Returns base64 encoded request snapshot.
     *
     * @return string
     */
    public function getSnapshot()
    {
        return base64_encode(json_encode($this->cardContent));
    }
}

