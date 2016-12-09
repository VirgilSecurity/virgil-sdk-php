<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


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
    function jsonSerialize()
    {
        return array_filter(
            [
                'device'      => $this->device,
                'device_name' => $this->deviceName,
            ],
            function ($value) {
                return count($value) !== 0;
            }
        );
    }
}
