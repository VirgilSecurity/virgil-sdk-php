<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


use Virgil\Sdk\Client\VirgilCards\Constants\JsonProperties;

use Virgil\Sdk\Client\VirgilServices\Model\AbstractModel;

/**
 * Class represents json serializable revoke card content model.
 */
class RevokeCardContentModel extends AbstractModel
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
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [
            JsonProperties::CARD_ID_ATTRIBUTE_NAME           => $this->id,
            JsonProperties::REVOCATION_REASON_ATTRIBUTE_NAME => $this->revocationReason,
        ];
    }
}
