<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


/**
 * Class keeps content and meta information for any singable request to Virgil Cards Service.
 */
class SignedRequestModel
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
}

