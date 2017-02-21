<?php
namespace Virgil\Sdk\Exceptions;


/**
 * Class specifies exception if disabled method is called.
 */
class MethodIsDisabledException extends VirgilRuntimeException
{
    const DEFAULT_MESSAGE = 'Method "%s" is disabled for this class.';


    /**
     * Class constructor.
     *
     * @param string $methodName
     */
    public function __construct($methodName)
    {
        parent::__construct(sprintf(self::DEFAULT_MESSAGE, $methodName));
    }

}
