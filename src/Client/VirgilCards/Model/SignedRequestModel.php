<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


use JsonSerializable;

/**
 * Class keeps content and meta information for any singable request to Virgil Cards Service.
 */
class SignedRequestModel
{
    /** @var JsonSerializable $cardContent */
    protected $cardContent;

    /** @var JsonSerializable $meta */
    protected $meta;


    /**
     * Class constructor.
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

