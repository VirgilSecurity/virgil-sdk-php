<?php
namespace Virgil\Sdk;


/**
 * Interface for manipulate with raw data.
 */
interface BufferInterface
{
    /**
     * Returns raw data base64 string
     *
     * @return string
     */
    public function toBase64();


    /**
     * Returns raw data hex string
     *
     * @return string
     */
    public function toHex();


    /**
     * Returns raw data UTF-8 representation
     *
     * @return string
     */
    public function toString();


    /**
     * Returns raw data
     *
     * @return string
     */
    public function getData();
}
