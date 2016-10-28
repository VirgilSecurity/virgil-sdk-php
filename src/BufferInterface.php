<?php

namespace Virgil\SDK;


interface BufferInterface
{
    /**
     * Gets raw data into base64 string
     *
     * @return string
     */
    public function toBase64();

    /**
     * Gets raw data into hex string
     *
     * @return string
     */
    public function toHex();

    /**
     * Gets raw data into UTF8 string
     *
     * @return string
     */
    public function toString();

    /**
     * Gets raw data
     *
     * @return string
     */
    public function getData();
}