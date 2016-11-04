<?php

namespace Virgil\SDK\Client\Card\Model;


class SignedResponseModel
{
    private $id;
    private $cardContent;
    private $meta;

    /**
     * SignedResponseModel constructor.
     * @param string $id
     * @param CardContentModel $cardContent
     * @param SignedResponseMetaModel $meta
     */
    public function __construct($id, CardContentModel $cardContent, SignedResponseMetaModel $meta)
    {
        $this->id = $id;
        $this->cardContent = $cardContent;
        $this->meta = $meta;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return CardContentModel
     */
    public function getCardContent()
    {
        return $this->cardContent;
    }

    /**
     * @return SignedResponseMetaModel
     */
    public function getMeta()
    {
        return $this->meta;
    }
}