<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class represents json serializable card content model.
 */
class CardContentModel extends AbstractModel
{
    /** @var string $identity */
    private $identity;

    /** @var string $identityType */
    private $identityType;

    /** @var string $publicKey */
    private $publicKey;

    /** @var array $data */
    private $data;

    /** @var string $scope */
    private $scope;

    /** @var DeviceInfoModel $info */
    private $info;


    /**
     * Class constructor.
     *
     * @param string          $identity
     * @param string          $identityType
     * @param string          $publicKey
     * @param array           $data
     * @param string          $scope
     * @param DeviceInfoModel $info
     */
    public function __construct(
        $identity,
        $identityType,
        $publicKey,
        $scope,
        array $data = [],
        DeviceInfoModel $info = null
    ) {
        $this->identity = $identity;
        $this->identityType = $identityType;
        $this->publicKey = $publicKey;
        $this->data = $data;
        $this->scope = $scope;
        $this->info = $info === null ? new DeviceInfoModel() : $info;
    }


    /**
     * Returns identity.
     *
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }


    /**
     *  Returns identity type.
     *
     * @return string
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }


    /**
     * Returns public key.
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }


    /**
     * Returns additional data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Returns scope.
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }


    /**
     * Returns device info model.
     *
     * @return DeviceInfoModel
     */
    public function getInfo()
    {
        return $this->info;
    }


    /**
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [
            JsonProperties::IDENTITY_ATTRIBUTE_NAME      => $this->identity,
            JsonProperties::IDENTITY_TYPE_ATTRIBUTE_NAME => $this->identityType,
            JsonProperties::PUBLIC_KEY_ATTRIBUTE_NAME    => $this->publicKey,
            JsonProperties::DATA_ATTRIBUTE_NAME          => $this->data,
            JsonProperties::SCOPE_ATTRIBUTE_NAME         => $this->scope,
            JsonProperties::INFO_ATTRIBUTE_NAME          => $this->info,
        ];
    }
}
