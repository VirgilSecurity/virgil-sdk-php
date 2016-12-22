<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\AbstractJsonModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\RevokeCardContentModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

/**
 * Class transforms revocation request model to json and vise versa.
 */
class RevokeRequestModelMapper extends AbstractJsonModelMapper
{
    /** @var SignedRequestModelMapper $signedRequestModelMapper */
    private $signedRequestModelMapper;


    /**
     * Class constructor.
     *
     * @param SignedRequestModelMapper $signedRequestModelMapper
     */
    public function __construct(SignedRequestModelMapper $signedRequestModelMapper)
    {
        $this->signedRequestModelMapper = $signedRequestModelMapper;
    }


    /**
     * @inheritdoc
     *
     * @return SignedRequestModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);
        $cardContentData = json_decode(base64_decode($data['content_snapshot']), true);
        $cardMetaData = $data['meta'];

        $cardContentModel = new RevokeCardContentModel(
            $cardContentData['card_id'], $cardContentData['revocation_reason']
        );

        $cardMetaModel = new SignedRequestMetaModel($cardMetaData['signs']);

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
