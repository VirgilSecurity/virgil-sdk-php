<?php

namespace Virgil\SDK\Cryptography\CryptoAPI\Cipher;


use Virgil\Crypto\VirgilDataSource;

class VirgilStreamDataSource extends VirgilDataSource
{
    private $stream;

    private $dataChunk;

    /**
     * VirgilStreamDataSource constructor.
     * @param resource $stream
     * @param int $dataChunk
     */
    public function __construct($stream, $dataChunk = 1024)
    {
        parent::__construct($this);
        $this->stream = $stream;
        rewind($this->stream);
        $this->dataChunk = $dataChunk;
    }

    /**
     * Checks if there are data chunk to encrypt
     *
     * @return bool
     */
    public function hasData()
    {
        return !feof($this->stream);
    }

    /**
     * Read data chunk from stream for encrypt
     *
     * @return string
     */
    public function read()
    {
        return fread($this->stream, $this->dataChunk);
    }

    /**
     * Set stream pointer to begin
     *
     * @return bool
     */
    public function reset()
    {
        return rewind($this->stream);
    }
}