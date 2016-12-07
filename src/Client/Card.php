<?php
namespace Virgil\Sdk\Client;


use Virgil\Sdk\BufferInterface;

class Card
{
    private $id;
    private $identity;
    private $identityType;
    private $publicKey;
    private $scope;
    private $data;
    private $device;
    private $deviceName;
    private $signatures;
    private $version;
    private $snapshot;


    /**
     * Card constructor.
     *
     * @param string            $id
     * @param BufferInterface   $snapshot
     * @param string            $identity
     * @param string            $identityType
     * @param BufferInterface   $publicKey
     * @param string            $scope
     * @param array             $data
     * @param string            $device
     * @param string            $deviceName
     * @param string            $version
     * @param BufferInterface[] $signatures
     */
    public function __construct(
        $id,
        BufferInterface $snapshot,
        $identity,
        $identityType,
        BufferInterface $publicKey,
        $scope,
        array $data = [],
        $device = null,
        $deviceName = null,
        $version,
        array $signatures
    ) {
        $this->id = $id;
        $this->snapshot = $snapshot;
        $this->identity = $identity;
        $this->identityType = $identityType;
        $this->publicKey = $publicKey;
        $this->scope = $scope;
        $this->data = $data;
        $this->device = $device;
        $this->deviceName = $deviceName;
        $this->signatures = $signatures;
        $this->version = $version;
    }


    /**
     * Gets the public key.
     *
     * @return BufferInterface
     */
    public function getPublicKeyData()
    {
        return $this->publicKey;
    }


    /**
     * Gets the type of the identity.
     *
     * @return string
     */
    public function getIdentityType()
    {
        return $this->identityType;
    }


    /**
     * Gets the identity.
     *
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }


    /**
     * Gets the Virgil Card fingerprint.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Gets the scope.
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }


    /**
     * Gets the data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Get sign by signid.
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
     * Gets the signs.
     *
     * @return BufferInterface[]
     */
    public function getSignatures()
    {
        return $this->signatures;
    }


    /**
     * Gets the name of the device.
     *
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }


    /**
     * Gets the device.
     *
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }


    /**
     * Gets the version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }


    /**
     * Gets the Virgil Card snapshot.
     *
     * @return BufferInterface
     */
    public function getSnapshot()
    {
        return $this->snapshot;
    }
}
