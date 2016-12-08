<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


/**
 * Interface represents input and output for cipher operations.
 */
interface InputOutputInterface
{
    /**
     * @return mixed
     */
    public function getInput();


    /**
     * @return mixed
     */
    public function getOutput();
}
