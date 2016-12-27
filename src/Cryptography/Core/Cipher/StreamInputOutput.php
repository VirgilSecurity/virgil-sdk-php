<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


/**
 * Class provides input and output streams for cipher operations.
 */
class StreamInputOutput implements InputOutputInterface
{
    /** @var  VirgilStreamDataSource $input */
    private $input;

    /** @var VirgilStreamDataSink $output */
    private $output;


    /**
     * Class constructor.
     *
     * @param resource $input  is stream source.
     * @param resource $output is stream destination.
     */
    public function __construct($input, $output)
    {
        $this->input = new VirgilStreamDataSource($input);
        $this->output = new VirgilStreamDataSink($output);
        $this->input->reset();
    }


    /**
     * @return VirgilStreamDataSource
     */
    public function getInput()
    {
        return $this->input;
    }


    /**
     * @return VirgilStreamDataSink
     */
    public function getOutput()
    {
        return $this->output;
    }
}
