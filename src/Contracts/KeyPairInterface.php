<?php
namespace Virgil\Sdk\Contracts;


/**
 * Interface represents an asymmetric key pair that is comprised of both public and private keys.
 */
interface KeyPairInterface
{
    /**
     * Gets public key.
     *
     * @return PublicKeyInterface
     */
    public function getPublicKey();


    /**
     * Gets private key.
     *
     * @return PrivateKeyInterface
     */
    public function getPrivateKey();
}
