<?php
namespace Virgil\Sdk\Client\VirgilIdentity\Mapper;


use InvalidArgumentException;

use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyRequestModel;

use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractJsonModelMapper;

/**
 * Class transforms verify request model to json.
 */
class VerifyRequestModelMapper extends AbstractJsonModelMapper
{
    /**
     * @inheritdoc
     */
    public function toJson($model)
    {
        /** @var VerifyRequestModel $model */
        if (!$model instanceof VerifyRequestModel) {
            throw new InvalidArgumentException('Invalid model passed. Instance of VerifyRequestModel accept only.');
        }

        return json_encode($model);
    }
}
