<?php
namespace Virgil\Sdk;


/**
 * Class provides ability to represent raw data in different formats.
 *
 * There are static methods allow to create class instances from different formats.
 *
 */
class Buffer implements BufferInterface
{
    /** @var string $rawData */
    protected $rawData;


    /**
     * Class constructor.
     *
     * @param string $rawData
     */
    public function __construct($rawData)
    {
        $this->rawData = $rawData;
    }


    /**
     * Creates self by base64 decoded string.
     *
     * @param string $base64String
     *
     * @return Buffer
     */
    public static function fromBase64($base64String)
    {
        return new self(base64_decode($base64String));
    }


    /**
     * Creates self by hex decoded string.
     *
     * @param string $hex
     *
     * @return Buffer
     */
    public static function fromHex($hex)
    {
        return new self(hex2bin($hex));
    }


    /**
     * @inheritdoc
     */
    public function toBase64()
    {
        return base64_encode($this->rawData);
    }


    /**
     * @inheritdoc
     */
    public function toHex()
    {
        return bin2hex($this->rawData);
    }


    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->rawData;
    }


    /**
     * @inheritdoc
     */
    public function toString()
    {
        return mb_convert_encoding($this->rawData, 'UTF-8');
    }


    public function __toString()
    {
        return $this->rawData;
    }
}
