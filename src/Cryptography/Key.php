<?php

namespace Virgil\SDK\Cryptography;


interface Key
{
    /**
     * @return string
     */
    public function getReceiverId();

    /**
     * @return string
     */
    public function getValue();
}