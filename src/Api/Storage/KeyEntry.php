<?php
namespace Virgil\Sdk\Api\Storage;


/**
 * Class represents the private key storage entry.
 */
class KeyEntry
{
    /** @var string */
    private $name;

    /** @var mixed */
    private $value;

    /** @var array */
    private $metadata;


    /**
     * Class constructor.
     *
     * @param string $name
     * @param mixed  $value
     * @param array  $metadata
     */
    public function __construct($name, $value, array $metadata = [])
    {
        $this->name = $name;
        $this->value = $value;
        $this->metadata = $metadata;
    }


    /**
     * Gets the key entry name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Gets the key entry value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * Gets the meta data associated with key entry.
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

}
