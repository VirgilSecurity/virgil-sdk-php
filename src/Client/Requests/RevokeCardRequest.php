<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Buffer;

use Virgil\Sdk\Client\VirgilCards\Mapper\RevokeRequestModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SignedRequestModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;

/**
 * Class represents request for card revoking.
 */
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
        $requestModelJsonMapper = self::getRequestModelJsonMapper();
        $modelJson = base64_decode($exportedRequest);
        $model = $requestModelJsonMapper->toModel($modelJson);

        /** @var RevokeCardContentModel $cardContent */
        $cardContent = $model->getCardContent();
        $request = new self($cardContent->getId(), $cardContent->getRevocationReason());

        /** @var SignedRequestMetaModel $meta */
        // TODO I think you can move that into helper method to prevent repeating there and inside the CreateCardRequest.php
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
        $requestModelJsonMapper = self::getRequestModelJsonMapper();

        return base64_encode($requestModelJsonMapper->toJson($this->getRequestModel()));
    }


    /**
     * @inheritdoc
     */
    protected function getCardContent()
    {
        return new RevokeCardContentModel($this->id, $this->reason);
    }
}
