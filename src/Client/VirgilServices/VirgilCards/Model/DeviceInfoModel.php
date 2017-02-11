<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilServices\VirgilCards\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilServices\Model\AbstractModel;

/**
 * Class represents json serializable card device info.
 */
class DeviceInfoModel extends AbstractModel
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
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [
            JsonProperties::INFO_DEVICE_ATTRIBUTE_NAME      => $this->device,
            JsonProperties::INFO_DEVICE_NAME_ATTRIBUTE_NAME => $this->deviceName,
        ];
    }
}
