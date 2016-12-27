<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Client\VirgilCards\Mapper\RevokeRequestModelMapper;
use Virgil\Sdk\Client\VirgilCards\Mapper\SignedRequestModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\RevokeCardContentModel;

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
     * Returns revoke request model mapper.
     *
     * @return RevokeRequestModelMapper
     */
    protected static function getRequestModelJsonMapper()
    {
        return new RevokeRequestModelMapper(new SignedRequestModelMapper());
    }


    /**
     * Builds self from revoke card content model.
     *
     * @param RevokeCardContentModel $cardContent
     *
     * @return RevokeCardRequest
     */
    protected static function buildRequestFromCardContent(RevokeCardContentModel $cardContent)
    {
        return new self($cardContent->getId(), $cardContent->getRevocationReason());
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