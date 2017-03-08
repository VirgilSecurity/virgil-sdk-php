<?php
namespace Virgil\Sdk\Client\Validator;


use Virgil\Sdk\Contracts\BufferInterface;

/**
 * Interface represents an information about Virgil Card need for validation.
 */
interface CardVerifierInfoInterface
{
    /**
     * Gets the card id.
     *
     * @return string
     */
    public function getCardId();


    /**
     * Gets the Public Key value.
     *
     * @return BufferInterface
     */
    public function getPublicKeyData();
}
