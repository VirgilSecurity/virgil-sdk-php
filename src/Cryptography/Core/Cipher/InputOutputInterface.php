<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


/**
 * Interface represents input and output for cipher operations.
 *
 * TODO: I know this interface is ugly. I was forced introduce it just to avoid code duplication into VirgilCrypto.
 * TODO: Better ideas are welcome.
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
