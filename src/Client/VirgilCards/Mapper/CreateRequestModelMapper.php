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

    /** @var SignedResponseModelMapper $signedResponseModelMapper */
    private $signedResponseModelMapper;


    /**
     * Class constructor.
     *
     * @param SignedRequestModelMapper  $signedRequestModelMapper
     * @param SignedResponseModelMapper $signedResponseModelMapper
     */
    public function __construct(
        SignedRequestModelMapper $signedRequestModelMapper,
        SignedResponseModelMapper $signedResponseModelMapper
    ) {
        $this->signedRequestModelMapper = $signedRequestModelMapper;
        $this->signedResponseModelMapper = $signedResponseModelMapper;
    }


    /**
     * @inheritdoc
     *
     * @return SignedRequestModel
     */
    public function toModel($json)
    {
        $signedResponseModel = $this->signedResponseModelMapper->toModel($json);

        $signedResponseModelMeta = $signedResponseModel->getMeta();

        $cardContentModel = $signedResponseModel->getCardContent();

        $cardMetaModel = new SignedRequestMetaModel($signedResponseModelMeta->getSigns());

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
