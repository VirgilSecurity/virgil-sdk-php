<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class represents json serializable card device info.
 */
class DeviceInfoModel implements JsonSerializable
{
    /** @var null|string $device */
    private $device;

    /** @var null|string $deviceName */
    private $deviceName;


    /**
     * Class constructor.
     *
     * @param string $device
     * @param string $deviceName
     */
    public function __construct($device = null, $deviceName = null)
    {
        $this->device = $device;
        $this->deviceName = $deviceName;
    }


    /**
     * Returns device name.
     *
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }


    /**
     * Returns device.
     *
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $data = [];

        if ($this->device != null) {
            $data[JsonProperties::INFO_DEVICE_ATTRIBUTE_NAME] = $this->device;
        }
        if ($this->deviceName != null) {
            $data[JsonProperties::INFO_DEVICE_NAME_ATTRIBUTE_NAME] = $this->deviceName;
        }

        return $data;
    }
}
