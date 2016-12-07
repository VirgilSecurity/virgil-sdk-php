<?php
namespace Virgil\Sdk\Client\Card\Model;


use Countable;
use JsonSerializable;

/**
 * Base abstract class for serializable models.
 */
abstract class AbstractModel implements JsonSerializable, Countable
{
    /**
     * Specifies total model properties comprised of json key and its value for further json serialization.
     *
     * @return mixed
     */
    abstract function jsonSerialize();


    /**
     * Counts total model properties that going to be serialized.
     *
     * @return int
     */
    public function count()
    {
        return count($this->jsonSerialize());
    }


    /**
     * @inheritdoc
     */
    function __toString()
    {
        return json_encode($this);
    }
}
