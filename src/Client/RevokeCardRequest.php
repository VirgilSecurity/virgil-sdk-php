<?php

namespace Virgil\SDK\Client;


use Virgil\SDK\Buffer;
use Virgil\SDK\Client\Card\Mapper\RevokeRequestModelMapper;
use Virgil\SDK\Client\Card\Mapper\SignedRequestModelMapper;
use Virgil\SDK\Client\Card\Model\RevokeCardContentModel;
use Virgil\SDK\Client\Card\Model\SignedRequestMetaModel;

class RevokeCardRequest extends AbstractCardRequest
{
    private $id;
    private $reason;

    /**
     * RevokeCardRequest constructor.
     *
     * @param string $id
     * @param string $reason
     */
    public function __construct($id, $reason)
    {
        $this->id = $id;
        $this->reason = $reason;
    }

    /**
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    protected function getCardContent()
    {
        return new RevokeCardContentModel(
            $this->id,
            $this->reason
        );
    }

    /**
     * Exports card to base64 json string.
     *
     * @return string
     */
    public function export()
    {
        return base64_encode(self::getRequestModelJsonMapper()->toJson($this->getRequestModel()));
    }

    /**
     * Imports card from base64 json string.
     *
     * @param $exportedRequest
     * @return RevokeCardRequest
     */
    public static function import($exportedRequest)
    {
        $modelJson = base64_decode($exportedRequest);
        $model = self::getRequestModelJsonMapper()->toModel($modelJson);

        /** @var RevokeCardContentModel $cardContent */
        $cardContent = $model->getCardContent();
        $request = new self(
            $cardContent->getId(),
            $cardContent->getRevocationReason()
        );

        /** @var SignedRequestMetaModel $meta */
        $meta = $model->getMeta();
        foreach ($meta->getSigns() as $signKey => $sign) {
            $request->appendSignature($signKey, Buffer::fromBase64($sign));
        }

        return $request;
    }

    /**
     * @return RevokeRequestModelMapper
     */
    public static function getRequestModelJsonMapper()
    {
        return new RevokeRequestModelMapper(new SignedRequestModelMapper());
    }
}