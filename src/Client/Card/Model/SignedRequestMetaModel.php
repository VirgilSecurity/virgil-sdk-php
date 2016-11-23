<?php

namespace Virgil\SDK\Client\Card\Model;


use Virgil\SDK\AbstractJsonSerializable;

class SignedRequestMetaModel extends AbstractJsonSerializable
{
    private $signs;

    /**
     * SignedRequestMetaModel constructor.
     *
     * @param array $signs
     */
    public function __construct(array $signs)
    {
        $this->signs = $signs;
    }

    /**
     * @return array
     */
    public function getSigns()
    {
        return $this->signs;
    }

    function jsonSerialize()
    {
        return ['signs' => $this->signs];
    }
}