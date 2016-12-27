<?php
namespace Virgil\Sdk\Cryptography\Core\Exceptions;


use Virgil\Sdk\Cryptography\VirgilCryptoException;

/**
 * Class specifies exception if public and private keys don't belong to same key pair.
 */
class InvalidKeyPairException extends VirgilCryptoException
{
}
