<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class keeps information of any signed response from Virgil Cards Service.
 */
class SignedResponseModel extends AbstractModel
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


    /**
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [
            JsonProperties::ID_ATTRIBUTE_NAME               => $this->id,
            JsonProperties::CONTENT_SNAPSHOT_ATTRIBUTE_NAME => $this->contentSnapshot,
            JsonProperties::META_ATTRIBUTE_NAME             => $this->meta,
        ];
    }
}
