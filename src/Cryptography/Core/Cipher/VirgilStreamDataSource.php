<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


use Virgil\Crypto\VirgilDataSource;

/**
 * Class is representation of data provider stream.
 */
class VirgilStreamDataSource extends VirgilDataSource
{
    /** @var resource $stream */
    private $stream;

    /** @var int $dataChunk */
    private $dataChunk;


    /**
     * Class constructor.
     *
     * @param resource $stream
     * @param int      $dataChunk specifies length number of bytes read.
     */
    public function __construct($stream, $dataChunk = 1024)
    {
        parent::__construct($this);
        $this->stream = $stream;
        rewind($this->stream);
        $this->dataChunk = $dataChunk;
    }


    /**
     * Checks if there is data chunk.
     *
     * @return bool
     */
    public function hasData()
    {
        return !feof($this->stream);
    }


    /**
     * Read data chunk from stream.
     *
     * @return string
     */
    public function read()
    {
        return fread($this->stream, $this->dataChunk);
    }


    /**
     * Set pointer to begin of the stream.
     *
     * @return bool
     */
    public function reset()
    {
        return rewind($this->stream);
    }
}
