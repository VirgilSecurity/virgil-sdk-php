<?php

namespace Virgil\Sdk\Client\VirgilServices;


/**
 * Base class for Virgil services errors.
 */
abstract class AbstractErrorMessages
{
    /**
     * Returns appropriate message for given service error code.
     *
     * @param int $errorCode
     *
     * @return string
     */
    public static function getMessage($errorCode)
    {
        $self = new static();
        $messagesList = $self->getErrorsList();

        return $messagesList[$errorCode];
    }


    /**
     * @return array
     */
    abstract protected function getErrorsList();
}
