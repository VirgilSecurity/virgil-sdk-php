<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


/**
 * Class keeps information of any signed response from Virgil Cards Service.
 */
class SignedResponseModel
{
    /** @var string $id */
    private $id;

    /** @var CardContentModel $cardContent */
    private $cardContent;

    /** @var SignedResponseMetaModel $meta */
    private $meta;

    /** @var string $contentSnapshot */
    private $contentSnapshot;


    /**
     * Class constructor.
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
     * Returns card id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Returns card content.
     *
     * @return CardContentModel
     */
    public function getCardContent()
    {
        return $this->cardContent;
    }


    /**
     * Returns card meta.
     *
     * @return SignedResponseMetaModel
     */
    public function getMeta()
    {
        return $this->meta;
    }


    /**
     * Returns card content snapshot.
     *
     * @return string
     */
    public function getSnapshot()
    {
        return $this->contentSnapshot;
    }
}
