<?php

namespace Virgil\SDK\Client\Card\Model;


use Virgil\SDK\AbstractJsonSerializable;

class RevokeCardContentModel extends AbstractJsonSerializable
{
    private $id;
    private $revocationReason;

    /**
     * RevokeCardContentModel constructor.
     *
     * @param string $id
     * @param string $revocationReason
     */
    public function __construct($id, $revocationReason)
    {
        $this->id = $id;
        $this->revocationReason = $revocationReason;
    }

    function jsonSerialize()
    {
        return [
            'card_id' => $this->id,
            'revocation_reason' => $this->revocationReason
        ];
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRevocationReason()
    {
        return $this->revocationReason;
    }
}