<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class keeps content and meta information of any signed request to Virgil Cards Service.
 */
class SignedRequestModel implements JsonSerializable
{
    /** @var JsonSerializable $requestContent */
    protected $requestContent;

    /** @var SignedRequestMetaModel $requestMeta */
    protected $requestMeta;

    /** @var string */
    private $contentSnapshot;


    /**
     * Class constructor.
     *
     * @param JsonSerializable       $requestContent
     * @param SignedRequestMetaModel $requestMeta
     * @param string                 $contentSnapshot base64 encoded string
     */
    public function __construct(
        JsonSerializable $requestContent,
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
     * @return JsonSerializable
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
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            JsonProperties::CONTENT_SNAPSHOT_ATTRIBUTE_NAME => $this->getSnapshot(),
            JsonProperties::META_ATTRIBUTE_NAME             => $this->requestMeta,
        ];
    }
}

