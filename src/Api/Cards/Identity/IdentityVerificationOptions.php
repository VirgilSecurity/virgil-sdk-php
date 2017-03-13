<?php
namespace Virgil\Sdk\Api\Cards\Identity;


/**
 * Class provides options for identity verification.
 */
class IdentityVerificationOptions implements IdentityVerificationOptionsInterface
{
    const COUNT_TO_LIVE = 1;
    const TIME_TO_LIVE = 3600;

    /** @var int */
    private $countToLive;

    /** @var int */
    private $timeToLive;

    /** @var array */
    private $extraFields;


    /**
     * Class constructor.
     *
     * @param int $countToLive
     * @param int $timeToLive
     * @param array $extraFields
     */
    public function __construct(
        array $extraFields = [],
        $countToLive = self::COUNT_TO_LIVE,
        $timeToLive = self::TIME_TO_LIVE
    ) {
        $this->countToLive = $countToLive;
        $this->timeToLive = $timeToLive;
        $this->extraFields = $extraFields;
    }


    /**
     * @inheritdoc
     */
    public function getExtraFields()
    {
        return $this->extraFields;
    }


    /**
     * @inheritdoc
     */
    public function getTimeToLive()
    {
        return $this->timeToLive;
    }


    /**
     * @inheritdoc
     */
    public function getCountToLive()
    {
        return $this->countToLive;
    }
}
