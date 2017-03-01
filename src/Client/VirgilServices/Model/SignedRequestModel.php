<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class keeps content and meta information of any signed request to Virgil Cards Service.
 */
class SignedRequestModel extends AbstractModel
{
    /** @var AbstractModel $requestContent */
    protected $requestContent;

    /** @var SignedRequestMetaModel $requestMeta */
    protected $requestMeta;

    /** @var string */
    private $contentSnapshot;


    /**
     * Class constructor.
     *
     * @param AbstractModel          $requestContent
     * @param SignedRequestMetaModel $requestMeta
     * @param string                 $contentSnapshot
     */
    public function __construct(
        AbstractModel $requestContent,
        SignedRequestMetaModel $requestMeta,
        $contentSnapshot = null
    ) {
        $this->requestContent = $requestContent;
        $this->requestMeta = $requestMeta;

        if ($contentSnapshot != null) {
            $this->contentSnapshot = $contentSnapshot;
        } else {
            $this->contentSnapshot = base64_encode(json_encode($this->requestContent));
        }
    }


    /**
     * @return AbstractModel
     */
    public function getRequestContent()
    {
        return $this->requestContent;
    }


    /**
     * @return SignedRequestMetaModel
     */
    public function getRequestMeta()
    {
        return $this->requestMeta;
    }


    /**
     * Returns base64 encoded request snapshot.
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
            JsonProperties::CONTENT_SNAPSHOT_ATTRIBUTE_NAME => $this->getSnapshot(),
            JsonProperties::META_ATTRIBUTE_NAME             => $this->requestMeta,
        ];
    }
}

