<?php
namespace Virgil\Sdk\Cryptography\Core\Cipher;


use RuntimeException;

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
     * @throws RuntimeException
     */
    public function getOutput()
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' is disabled for this class');
    }
}
