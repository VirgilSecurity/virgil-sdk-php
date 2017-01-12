<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Contracts\BufferInterface;

use Virgil\Sdk\Client\AbstractJsonModelMapper;

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
     * Imports card request from base64 json string.
     *
     * @param string $exportedSignedRequestModel base64 encoded request.
     *
     * @return AbstractCardRequest
     */
    public static function import($exportedSignedRequestModel)
    {
        /** @var AbstractJsonModelMapper $requestModelJsonMapper */
        $requestModelJsonMapper = static::getRequestModelJsonMapper();
        $modelJson = base64_decode($exportedSignedRequestModel);
        $model = $requestModelJsonMapper->toModel($modelJson);

        $cardContent = $model->getRequestContent();

        /** @var AbstractCardRequest $request */
        $request = static::buildRequestFromCardContent($cardContent);

        /** @var SignedRequestMetaModel $meta */
        $meta = $model->getRequestMeta();
        foreach ($meta->getSigns() as $signKey => $sign) {
            $request->appendSignature($signKey, Buffer::fromBase64($sign));
        }

        return $request;
    }


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
     */
    public function getSnapshot()
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
