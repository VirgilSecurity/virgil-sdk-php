<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\AbstractJsonModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

/**
 * Class transforms create request model to json and vise versa.
 */
class CreateRequestModelMapper extends AbstractJsonModelMapper
{
    /** @var SignedRequestModelMapper $signedRequestModelMapper */
    private $signedRequestModelMapper;

    /** @var CardContentModelMapper $cardContentModelMapper */
    private $cardContentModelMapper;


    /**
     * Class constructor.
     *
     * @param SignedRequestModelMapper  $signedRequestModelMapper
     * @param CardContentModelMapper $cardContentModelMapper
     */
    public function __construct(
        SignedRequestModelMapper $signedRequestModelMapper,
        CardContentModelMapper $cardContentModelMapper
    ) {
        $this->signedRequestModelMapper = $signedRequestModelMapper;
        $this->cardContentModelMapper = $cardContentModelMapper;
    }


    /**
     * @inheritdoc
     *
     * @return SignedRequestModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);
        $cardContentJson = base64_decode($data[self::CONTENT_SNAPSHOT_ATTRIBUTE_NAME]);
        $cardMetaData = $data[self::META_ATTRIBUTE_NAME];

        $cardContentModel = $this->cardContentModelMapper->toModel($cardContentJson);

        $cardMetaModel = new SignedRequestMetaModel($cardMetaData[self::SIGNS_ATTRIBUTE_NAME]);

        return new SignedRequestModel($cardContentModel, $cardMetaModel);
    }


    /**
     * @inheritdoc
     */
    public function toJson($model)
    {
        return $this->signedRequestModelMapper->toJson($model);
    }
}
