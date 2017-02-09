<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilServices\Model\AbstractModel;

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
            'identity'      => $this->identity,
            'identity_type' => $this->identityType,
            'public_key'    => $this->publicKey,
            'data'          => $this->data,
            'scope'         => $this->scope,
            'info'          => $this->info,
        ];
    }
}
