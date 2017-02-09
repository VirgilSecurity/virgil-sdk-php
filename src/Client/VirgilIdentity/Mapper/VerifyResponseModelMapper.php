<?php
namespace Virgil\Sdk\Client\VirgilIdentity\Mapper;


use Virgil\Sdk\Client\AbstractJsonModelMapper;

use Virgil\Sdk\Client\VirgilIdentity\Model\VerifyResponseModel;

/**
 * Class transforms verify response model to model.
 */
class VerifyResponseModelMapper extends AbstractJsonModelMapper
{
    const ACTION_ID_ATTRIBUTE_NAME = 'action_id';


    /**
     * @inheritdoc
     *
     * @return VerifyResponseModel
     */
    public function toModel($json)
    {
        $data = json_decode($json, true);

        return new VerifyResponseModel($data[self::ACTION_ID_ATTRIBUTE_NAME]);
    }
}
