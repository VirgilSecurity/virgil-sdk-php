<?php

namespace Virgil\SDK;


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