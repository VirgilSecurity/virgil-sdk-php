<?php

namespace Virgil\SDK\Client\Card\Model;


use Virgil\SDK\AbstractJsonSerializable;

class DeviceInfoModel extends AbstractJsonSerializable
{
    private $device;
    private $deviceName;

    /**
     * DeviceInfo constructor.
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
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }

    /**
     * @return string
     */
    public function getDevice()
    {
        return $this->device;
    }

    function jsonSerialize()
    {
        return array_filter([
            'device' => $this->device,
            'device_name' => $this->deviceName
        ], function ($value) {
            return count($value) !== 0;
        });
    }
}