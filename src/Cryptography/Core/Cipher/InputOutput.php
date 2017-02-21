<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


use Virgil\Sdk\Exceptions\MethodIsDisabledException;

/**
 * Class provides content input for cipher operations.
 */
class InputOutput implements InputOutputInterface
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
     * @inheritdoc
     */
    public function getInput()
    {
        return $this->input;
    }


    /**
     * @inheritdoc
     *
     * @throws MethodIsDisabledException
     */
    public function getOutput()
    {
        throw new MethodIsDisabledException(__METHOD__);
    }
}
