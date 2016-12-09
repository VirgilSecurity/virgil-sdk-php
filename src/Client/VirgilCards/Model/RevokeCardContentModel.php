<?php
namespace Virgil\Sdk\Client\VirgilCards\Model;


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
     * @inheritdoc
     */
    function jsonSerialize()
    {
        return [
            'card_id'           => $this->id,
            'revocation_reason' => $this->revocationReason,
        ];
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
}
