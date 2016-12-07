<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


use RuntimeException;

class CipherInputOutput implements CipherInputOutputInterface
{
    /** @var string $input */
    private $input;


    /**
     * Class constructor.
     *
     * @param string $input
     */
    public function __construct($input)
    {
        $this->input = $input;
    }


    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }


    /**
     * @throws RuntimeException
     */
    public function getOutput()
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' is disabled for this class');
    }


    //public function setOutput($output)
    //{
    //    $this->output = $output;
    //}
    //
    //
    //public function setInput($input)
    //{
    //    $this->input = $input;
    //}
}
