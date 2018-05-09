<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model;


use JsonSerializable;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Constants\JsonProperties;

/**
 * Class represents confirm token model.
 */
class TokenModel implements JsonSerializable
{
    /** @var string */
    private $timeToLive;

    /** @var string */
    private $countToLive;


    /**
     * Class constructor.
     *
     * @param string $timeToLive
     * @param string $countToLive
     */
    public function __construct($timeToLive, $countToLive)
    {
        $this->timeToLive = $timeToLive;
        $this->countToLive = $countToLive;
    }


    /**
     * @return string
     */
    public function getTimeToLive()
    {
        return $this->timeToLive;
    }


    /**
     * @return string
     */
    public function getCountToLive()
    {
        return $this->countToLive;
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
            JsonProperties::TIME_TO_LIVE_ATTRIBUTE_NAME  => $this->timeToLive,
            JsonProperties::COUNT_TO_LIVE_ATTRIBUTE_NAME => $this->countToLive,
        ];
    }
}
