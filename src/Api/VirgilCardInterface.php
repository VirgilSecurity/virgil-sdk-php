<?php
namespace Virgil\Sdk\Api;


use Virgil\Sdk\Contracts\PublicKeyInterface;

interface VirgilCardInterface
{
    /**
     * Returns public key.
     *
     * @return PublicKeyInterface
     */
    public function getPublicKey();
}
