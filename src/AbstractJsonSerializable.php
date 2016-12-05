<?php
namespace Virgil\Sdk;


abstract class AbstractJsonSerializable implements \JsonSerializable, \Countable
{
    abstract function jsonSerialize();

    public function count()
    {
        return count($this->jsonSerialize());
    }

    function __toString()
    {
        return json_encode($this);
    }
}
