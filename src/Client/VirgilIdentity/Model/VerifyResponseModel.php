<?php
namespace Virgil\Sdk\Client\VirgilIdentity\Model;


/**
 * Class represents verify response model.
 */
class VerifyResponseModel
{
    /** @var string */
    private $actionId;


    /**
     * Class constructor.
     *
     * @param string $actionId
     */
    public function __construct($actionId)
    {
        $this->actionId = $actionId;
    }


    /**
     * @return string
     */
    public function getActionId()
    {
        return $this->actionId;
    }
}
