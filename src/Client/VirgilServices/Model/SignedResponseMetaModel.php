<?php
namespace Virgil\Sdk\Client\VirgilServices\Model;


use DateTime;

use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\Constants\JsonProperties;

/**
 * Class represents card meta model response.
 */
class SignedResponseMetaModel implements JsonSerializable
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
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $data = [
            JsonProperties::CREATED_AT_ATTRIBUTE_NAME   => $this->createdAt->format(DateTIme::ISO8601),
            JsonProperties::CARD_VERSION_ATTRIBUTE_NAME => $this->cardVersion,
        ];

        if (count($this->signs)) {
            $data[JsonProperties::SIGNS_ATTRIBUTE_NAME] = $this->signs;
        }

        return $data;
    }
}
