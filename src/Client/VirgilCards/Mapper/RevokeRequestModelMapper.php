<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\VirgilCards\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

/**
 * Class transforms revocation request model to json and vise versa.
 */
class RevokeRequestModelMapper extends SignedRequestModelMapper
{
    const ID_ATTRIBUTE_NAME = 'card_id';
    const REVOCATION_REASON_ATTRIBUTE_NAME = 'revocation_reason';


    /**
     * @inheritdoc
     *
     * @return SignedRequestModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);
        $cardContentData = json_decode(base64_decode($data[self::CONTENT_SNAPSHOT_ATTRIBUTE_NAME]), true);
        $cardMetaData = $data[self::META_ATTRIBUTE_NAME];

        $cardContentModel = new RevokeCardContentModel(
            $cardContentData[self::ID_ATTRIBUTE_NAME], $cardContentData[self::REVOCATION_REASON_ATTRIBUTE_NAME]
        );

        $cardMetaModel = new SignedRequestMetaModel($cardMetaData[self::SIGNS_ATTRIBUTE_NAME]);

        return new SignedRequestModel($cardContentModel, $cardMetaModel);
    }
}
