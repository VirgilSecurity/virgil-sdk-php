<?php

namespace Virgil\SDK\Cryptography\CryptoAPI;


interface CryptoAPI
{
    /**
     * @param integer $type Key generation type
     * @return KeyPair
     */
    public function generate($type);

    /**
     * @param string $key
     * @return mixed
     */
    public function privateKeyToDER($key);

    /**
     * @param string $key
     * @return mixed
     */
    public function publicKeyToDER($key);

    /**
     * @param string $publicKey
     * @param string $privateKey
     * @return mixed
     */
    public function isKeyPairMatch($publicKey, $privateKey);

    /**
     * @param mixed $key DER public key value
     * @param integer $algorithm Hash algorithm
     * @return mixed
     */
    public function computeKeyHash($key, $algorithm);

    /**
     * @param string $privateKey
     * @param string $password
     * @return mixed
     */
    public function extractPublicKey($privateKey, $password);

    /**
     * @param string $privateKey
     * @param string $password
     * @return mixed
     */
    public function encryptPrivateKey($privateKey, $password);

    /**
     * @param string $privateKey
     * @param string $password
     * @return mixed
     */
    public function decryptPrivateKey($privateKey, $password);
}