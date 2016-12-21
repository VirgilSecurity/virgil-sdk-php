<?php
namespace Virgil\Sdk\Client\VirgilCards\Mapper;


use Virgil\Sdk\Client\AbstractJsonModelMapper;

use Virgil\Sdk\Client\VirgilCards\Model\SignedRequestModel;

/**
 * Class transforms signed request model to json.
 */
class SignedRequestModelMapper extends AbstractJsonModelMapper
{
    /**
     * @inheritdoc
     */
    public function toJson($model)
    {
        /** @var SignedRequestModel $model */
        if (!$model instanceof SignedRequestModel) {
            // TODO  \InvalidArgumentException move into global namespace and use then only InvalidArgumentException
            throw new \InvalidArgumentException('Invalid model passed. Instance of SignedRequestModel accept only.');
        }

        return json_encode(
            [
                'content_snapshot' => $model->getSnapshot(),
                'meta'             => $model->getMeta(),
            ]
        );
    }
}
