<?php
namespace Virgil\Sdk\Client\Http;


class Status implements StatusInterface
{
    private $status;


    /**
     * Status constructor.
     *
     * @param string $status
     */
    public function __construct($status)
    {
        $this->status = $status;
    }


    /**
     * @inheritdoc
     */
    public function isSuccess()
    {
        return strval($this->status)[0] === '2';
    }


    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return $this->status;
    }
}
