<?php
namespace Virgil\Sdk\Cryptography;


use Virgil\Sdk\Contracts\PrivateKeyInterface;
use Virgil\Sdk\Cryptography\KeyEntryStorage\KeyReference;

/**
 * Class represents reference to material private key that is managed by the agent.
 */
class PrivateKeyReference extends KeyReference implements PrivateKeyInterface
{
}
