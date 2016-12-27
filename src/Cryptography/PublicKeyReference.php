<?php
namespace Virgil\Sdk\Cryptography;


use Virgil\Sdk\Contracts\PublicKeyInterface;
use Virgil\Sdk\Cryptography\KeyEntryStorage\KeyReference;

/**
 * Class represents reference to material public key that is managed by the agent.
 */
class PublicKeyReference extends KeyReference implements PublicKeyInterface
{
}
