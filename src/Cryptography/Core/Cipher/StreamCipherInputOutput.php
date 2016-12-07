<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


class StreamCipherInputOutput implements CipherInputOutputInterface
{
    /** @var  VirgilStreamDataSource $input */
    private $input;

    /** @var VirgilStreamDataSink $output */
    private $output;


    /**
     * Class constructor.
     *
     * @param resource $input
     * @param resource $output
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


    //public function setOutput($output)
    //{
    //    $this->output = new VirgilStreamDataSink($output);
    //}
    //
    //
    //public function setInput($input)
    //{
    //    $this->input = new VirgilStreamDataSource($input);
    //}
}
