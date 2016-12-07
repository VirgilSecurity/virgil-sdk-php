<?php
namespace Virgil\Sdk\Client\Card\Model;


class SignedResponseModel
{
    private $id;
    private $cardContent;
    private $meta;
    private $contentSnapshot;


    /**
     * SignedResponseModel constructor.
     *
     * @param string                  $id
     * @param string                  $contentSnapshot
     * @param CardContentModel        $cardContent
     * @param SignedResponseMetaModel $meta
     */
    public function __construct($id, $contentSnapshot, CardContentModel $cardContent, SignedResponseMetaModel $meta)
    {
        $this->id = $id;
        $this->cardContent = $cardContent;
        $this->meta = $meta;
        $this->contentSnapshot = $contentSnapshot;
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


    /**
     * @return string
     */
    public function getSnapshot()
    {
        return $this->contentSnapshot;
    }
}
