<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\BufferInterface;

use Virgil\Sdk\Client\VirgilCards\Model\AbstractModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

/**
 * Class is a base class for request which requires signatures.
 */
abstract class AbstractCardRequest
{
    /** @var BufferInterface[] $signatures */
    protected $signatures = [];


    /**
     * Returns the request model.
     *
     * @return SignedRequestModel
     */
    public function getRequestModel()
    {
        return new SignedRequestModel($this->getCardContent(), $this->getCardMeta());
    }


    /**
     * Append signature to request.
     *
     * @param string          $signatureId
     * @param BufferInterface $signature
     */
    public function appendSignature($signatureId, BufferInterface $signature)
    {
        $this->signatures[$signatureId] = $signature;
    }


    /**
     * Returns the signatures.
     *
     * @return array
     */
    public function getSignatures()
    {
        return $this->signatures;
    }


    /**
     * Returns card request snapshot.
     *
     * @return string base64 encoded snapshot.
     * TODO missed 'get' prefix
     */
    public function snapshot()
    {
        $requestModel = $this->getRequestModel();

        return $requestModel->getSnapshot();
    }


    /**
     * Returns the card content.
     *
     * @return AbstractModel
     */
    protected abstract function getCardContent();


    /**
     * Returns the card meta.
     *
     * @return SignedRequestMetaModel
     * TODO sometimes I see $signature->toBase64() sometimes Buffer::fromBase64 what the difference?
     */
    protected function getCardMeta()
    {
        $signatureToBase64Encode = function (BufferInterface $signature) {
            return $signature->toBase64();
        };

        $signatures = array_map($signatureToBase64Encode, $this->signatures);

        return new SignedRequestMetaModel($signatures);
    }
}
