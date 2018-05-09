<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


use VirgilDataSink as CryptoVirgilDataSink;

/**
 * Class is representation of data consumer stream.
 */
class VirgilStreamDataSink extends CryptoVirgilDataSink
{
    /** @var resource $stream */
    private $stream;


    /**
     * Class constructor.
     *
     * @param resource $stream
     */
    public function __construct($stream)
    {
        parent::__construct($this);
        $this->stream = $stream;
    }


    /**
     * Checks if sink stream is good for write.
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
     * Write chunk of encrypted data to sink stream.
     *
     * @param string $data
     *
     * @return int
     */
    function write($data)
    {
        return fwrite($this->stream, $data);
    }
}
