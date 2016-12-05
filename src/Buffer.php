<?php
namespace Virgil\Sdk;


class Buffer implements BufferInterface
{
    protected $rawData;

    /**
     * Buffer constructor.
     *
     * @param mixed $rawData
     */
    public function __construct($rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * Create self by base64 decoded string
     *
     * @param $base64String
     * @return Buffer
     */
    public static function fromBase64($base64String)
    {
        return new self(base64_decode($base64String));
    }

    /**
     * Create self by hex decoded string
     *
     * @param $hex
     * @return Buffer
     */
    public static function fromHex($hex)
    {
        return new self(hex2bin($hex));
    }

    public function toBase64()
    {
        return base64_encode($this->rawData);
    }

    public function toHex()
    {
        return bin2hex($this->rawData);
    }


    public function getData()
    {
        return $this->rawData;
    }


    public function toString()
    {
        return mb_convert_encoding($this->rawData, 'UTF-8');
    }
}
