<?php
namespace Virgil\Sdk\Client;


use DateTime;

use Virgil\Sdk\Contracts\BufferInterface;

/**
 * Class represents Virgil Cards Service entity.
 */
class Card
{
    /** @var string $id */
    private $id;

    /** @var string $identity */
    private $identity;

    /** @var string $identityType */
    private $identityType;

    /** @var BufferInterface $publicKeyData */
    private $publicKeyData;

    /** @var string $scope */
    private $scope;

    /** @var array $data */
    private $data;

    /** @var null|string $device */
    private $device;

    /** @var null|string $deviceName */
    private $deviceName;

    /** @var BufferInterface[] $signatures */
    private $signatures;

    /** @var string $version */
    private $version;

    /** @var BufferInterface $snapshot */
    private $snapshot;

    /** @var DateTime */
    private $createdAt;


    /**
     * Class constructor.
     *
     * @param string            $id
     * @param BufferInterface   $snapshot
     * @param string            $identity
     * @param string            $identityType
     * @param BufferInterface   $publicKeyData
     * @param string            $scope
     * @param array             $data
     * @param string            $device
     * @param string            $deviceName
     * @param string            $version
     * @param BufferInterface[] $signatures
     * @param DateTime          $createdAt
     */
    public function __construct(
        $id,
        BufferInterface $snapshot,
        $identity,
        $identityType,
        BufferInterface $publicKeyData,
        $scope,
        array $data = [],
        $device = null,
        $deviceName = null,
        $version,
        array $signatures,
        DateTime $createdAt
    ) {
        $this->id = $id;
        $this->snapshot = $snapshot;
        $this->identity = $identity;
        $this->identityType = $identityType;
        $this->publicKeyData = $publicKeyData;
        $this->scope = $scope;
        $this->data = $data;
        $this->device = $device;
        $this->deviceName = $deviceName;
        $this->signatures = $signatures;
        $this->version = $version;
        $this->createdAt = $createdAt;
    }


    /**
     * Returns the public key.
     *
     * @return BufferInterface
     */
    public function getPublicKeyData()
    {
        return $this->publicKeyData;
    }


    /**
     * Returns the type of the identity.
     *
     * @return string
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }


    /**
     * Returns the identity.
     *
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }


    /**
     * Returns the Virgil Card fingerprint.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Returns the scope.
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }


    /**
     * Returns the data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Returns sign by signid.
     *
     * @param string $signatureId
     *
     * @return BufferInterface
     */
    public function getSignature($signatureId)
    {
        return $this->signatures[$signatureId];
    }


    /**
     * Returns the signs.
     *
     * @return BufferInterface[]
     */
    public function getSignatures()
    {
        return $this->signatures;
    }


    /**
     * Returns the name of the device.
     *
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }


    /**
     * Returns the device.
     *
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }


    /**
     * Returns the version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }


    /**
     * Returns the Virgil Card snapshot.
     *
     * @return BufferInterface
     */
    public function getSnapshot()
    {
        return $this->snapshot;
    }


    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
