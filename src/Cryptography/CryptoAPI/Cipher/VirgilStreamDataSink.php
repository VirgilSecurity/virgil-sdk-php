<?php

namespace Virgil\SDK\Cryptography\CryptoAPI\Cipher;


use Virgil\Crypto\VirgilDataSink;

class VirgilStreamDataSink extends VirgilDataSink
{
    private $stream;

    /**
     * VirgilStreamDataSink constructor.
     * @param resource $stream
     */
    public function __construct($stream)
    {
        parent::__construct($this);
        $this->stream = $stream;
    }

    /**
     * Checks if sink stream is good to write
     *
     * @return bool
     */
    function isGood()
    {
        $meta = stream_get_meta_data($this->stream);
        $mode = $meta['mode'];
        return false === strpos($mode, 'r') || true === strpos($mode, 'r+');
    }

    /**
     * Write chunk of encrypted data to sink stream
     *
     * @param string $data
     * @return int
     */
    function write($data)
    {
        return fwrite($this->stream, $data);
    }
}