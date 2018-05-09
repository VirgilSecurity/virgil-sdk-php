<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class represents json serializable revoke card content model.
 */
class RevokeCardContentModel implements JsonSerializable
{
    /** @var string $id */
    private $id;

    /** @var string $revocationReason */
    private $revocationReason;


    /**
     * Class constructor.
     *
     * @param string $id
     * @param string $revocationReason
     */
    public function __construct($id, $revocationReason)
    {
        $this->id = $id;
        $this->revocationReason = $revocationReason;
    }


    /**
     * Returns card id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Returns card revocation reason.
     *
     * @return string
     */
    public function getRevocationReason()
    {
        return $this->revocationReason;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            JsonProperties::CARD_ID_ATTRIBUTE_NAME           => $this->id,
            JsonProperties::REVOCATION_REASON_ATTRIBUTE_NAME => $this->revocationReason,
        ];
    }
}
