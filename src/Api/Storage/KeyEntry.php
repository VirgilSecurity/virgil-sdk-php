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


    /**
     * Class constructor.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
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
}
