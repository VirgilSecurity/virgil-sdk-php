<?php

namespace Virgil\SDK\Client\Http;


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

    public function isSuccess()
    {
        return strval($this->status)[0] === '2';
    }

    public function getStatus()
    {
        return $this->status;
    }
}