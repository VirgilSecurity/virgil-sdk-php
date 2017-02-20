<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Contracts\BufferInterface;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;

/**
 * Class represents request for application card creation.
 */
class PublishCardRequest extends CreateCardRequest
{
    /**
     * Class constructor.
     *
     * @param string               $identity
     * @param string               $identityType
     * @param BufferInterface      $publicKeyData
     * @param array                $data
     * @param DeviceInfoModel|null $info
     */
    public function __construct(
        $identity,
        $identityType,
        BufferInterface $publicKeyData,
        array $data = [],
        DeviceInfoModel $info = null
    ) {
        parent::__construct($identity, $identityType, $publicKeyData, CardScopes::TYPE_APPLICATION, $data, $info);
    }
}
