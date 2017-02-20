<?php
namespace Virgil\Sdk\Client\Requests;


use Virgil\Sdk\Contracts\BufferInterface;

use Virgil\Sdk\Client\Requests\Constants\CardScopes;

use Virgil\Sdk\Client\VirgilServices\Model\SignedRequestMetaModel;
use Virgil\Sdk\Client\VirgilServices\Model\ValidationModel;
use Virgil\Sdk\Client\VirgilServices\Model\DeviceInfoModel;

/**
 * Class represents request for global card creation.
 */
class PublishGlobalCardRequest extends CreateCardRequest
{
    /** @var ValidationModel */
    private $validation;


    /**
     * Class constructor.
     *
     * @param string               $identity
     * @param string               $identityType
     * @param BufferInterface      $publicKeyData
     * @param ValidationModel      $validation
     * @param array                $data
     * @param DeviceInfoModel|null $info
     */
    public function __construct(
        $identity,
        $identityType,
        BufferInterface $publicKeyData,
        ValidationModel $validation = null,
        array $data = [],
        DeviceInfoModel $info = null
    ) {
        parent::__construct($identity, $identityType, $publicKeyData, CardScopes::TYPE_GLOBAL, $data, $info);

        $this->validation = $validation;
    }


    /**
     * @return ValidationModel
     */
    public function getValidation()
    {
        return $this->validation;
    }


    /**
     * @inheritdoc
     */
    protected function getCardMeta()
    {
        $cardMeta = parent::getCardMeta();

        return new SignedRequestMetaModel($cardMeta->getSigns(), $this->validation);
    }
}
