<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilServices\Model\AbstractModel;

/**
 * Class keeps content and meta information of any signed request to Virgil Cards Service.
 */
class SignedRequestModel extends AbstractModel
{
    /** @var AbstractModel $requestContent */
    protected $requestContent;

    /** @var SignedRequestMetaModel $requestMeta */
    protected $requestMeta;


    /**
     * Class constructor.
     *
     * @param AbstractModel          $requestContent
     * @param SignedRequestMetaModel $requestMeta
     */
    public function __construct(AbstractModel $requestContent, SignedRequestMetaModel $requestMeta)
    {
        $this->requestContent = $requestContent;
        $this->requestMeta = $requestMeta;
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
        return base64_encode(json_encode($this->requestContent));
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

