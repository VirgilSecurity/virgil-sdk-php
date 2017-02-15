<?php
namespace Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Model;


use Virgil\Sdk\Client\VirgilServices\Model\AbstractModel;

use Virgil\Sdk\Client\VirgilServices\VirgilIdentity\Constants\JsonProperties;

/**
 * Class represents confirm token model.
 */
class TokenModel extends AbstractModel
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
     * @inheritdoc
     */
    protected function jsonSerializeData()
    {
        return [
            JsonProperties::TIME_TO_LIVE_ATTRIBUTE_NAME  => $this->timeToLive,
            JsonProperties::COUNT_TO_LIVE_ATTRIBUTE_NAME => $this->countToLive,
        ];
    }
}
