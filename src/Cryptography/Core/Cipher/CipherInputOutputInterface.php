<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


interface CipherInputOutputInterface
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
