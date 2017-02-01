<?php
namespace Virgil\Sdk\Exceptions;


class MethodIsDisabledException extends VirgilRuntimeException
{
    const DEFAULT_MESSAGE = 'Method "%s" is disabled for this class.';


    public function __construct($methodName)
    {
        parent::__construct(sprintf(self::DEFAULT_MESSAGE, $methodName));
    }

}
