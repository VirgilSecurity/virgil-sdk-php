<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use DateTime;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class represents card meta model response.
 */
class SignedResponseMetaModel extends AbstractModel
{
    /** @var array $signs */
    private $signs;

    /** @var DateTime $createdAt */
    private $createdAt;

    /** @var string $cardVersion */
    private $cardVersion;


    /**
     * Class constructor.
     *
     * @param array    $signs
     * @param DateTime $createdAt
     * @param string   $cardVersion
     */
    public function __construct(array $signs, DateTime $createdAt, $cardVersion)
    {
        $this->signs = $signs;
        $this->createdAt = $createdAt;
        $this->cardVersion = $cardVersion;
    }


    /**
     * Returns card version.
     *
     * @return string
     */
    public function getCardVersion()
    {
        return $this->cardVersion;
    }


    /**
     * Returns create at date.
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * Returns signatures.
     *
     * @return array
     */
    public function getSigns()
    {
        return $this->signs;
    }


    /**
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [
            JsonProperties::CREATED_AT_ATTRIBUTE_NAME   => $this->createdAt->format(DateTIme::ISO8601),
            JsonProperties::CARD_VERSION_ATTRIBUTE_NAME => $this->cardVersion,
            JsonProperties::SIGNS_ATTRIBUTE_NAME        => $this->signs,
        ];
    }
}
