<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\Card\Mapper\RevokeRequestModelMapper;
use Virgil\Sdk\Client\Card\Mapper\SignedRequestModelMapper;

use Virgil\Sdk\Client\Card\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\Card\Model\SignedRequestMetaModel;

class RevokeCardRequest extends AbstractCardRequest
{
    /** @var string $id */
    private $id;

    /** @var string $reason */
    private $reason;


    /**
     * Class constructor.
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
     * Imports card request from base64 json string.
     *
     * @param $exportedRequest
     *
     * @return RevokeCardRequest
     */
    public static function import($exportedRequest)
    {
        $modelJson = base64_decode($exportedRequest);
        $model = self::getRequestModelJsonMapper()->toModel($modelJson);

        /** @var RevokeCardContentModel $cardContent */
        $cardContent = $model->getCardContent();
        $request = new self($cardContent->getId(), $cardContent->getRevocationReason());

        /** @var SignedRequestMetaModel $meta */
        $meta = $model->getMeta();
        foreach ($meta->getSigns() as $signKey => $sign) {
            $request->appendSignature($signKey, Buffer::fromBase64($sign));
        }

        return $request;
    }


    /**
     * Returns revoke request model mapper.
     *
     * @return RevokeRequestModelMapper
     */
    public static function getRequestModelJsonMapper()
    {
        return new RevokeRequestModelMapper(new SignedRequestModelMapper());
    }


    /**
     * Returns revocation reason.
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }


    /**
     * Returns card id to revoke.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
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


    protected function getCardContent()
    {
        return new RevokeCardContentModel($this->id, $this->reason);
    }
}
