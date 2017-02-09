<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


use Countable;
use JsonSerializable;

/**
 * Base abstract class for serializable models.
 */
abstract class AbstractModel implements JsonSerializable, Countable
{
    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter(
            $this->jsonSerializeData(),
            function ($value) {
                return count($value) !== 0;
            }
        );
    }


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


    /**
     * Specifies model properties comprised of json key and its value for further json serialization.
     *
     * @return mixed
     */
    abstract protected function jsonSerializeData();
}
