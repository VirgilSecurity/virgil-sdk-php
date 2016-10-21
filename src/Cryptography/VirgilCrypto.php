<?php

namespace Virgil\SDK\Cryptography;


use Virgil\SDK\Cryptography\CryptoAPI\VirgilCryptoAPI;

class VirgilCrypto implements Crypto
{
    /** @var VirgilCryptoAPI */
    private $cryptoAPI;

    /**
     * VirgilCrypto constructor.
     * @param VirgilCryptoAPI $cryptoApi
     */
    public function __construct(VirgilCryptoAPI $cryptoApi)
    {
        $this->cryptoAPI = $cryptoApi;
    }

    /**
     * @param integer $cryptoType
     * @throws VirgilCryptoException
     * @return VirgilKeyPair
     */
    public function generateKeys($cryptoType = VirgilCryptoType::DefaultType)
    {
        $key = $this->cryptoAPI->generate($cryptoType);
        $publicKeyDER = $this->cryptoAPI->publicKeyToDER($key->getPublicKey());
        $privateKeyDER = $this->cryptoAPI->privateKeyToDER($key->getPrivateKey());
        $publicKeyHash = $this->cryptoAPI->computeKeyHash($publicKeyDER, VirgilHashAlgorithmType::DefaultType);

        return new VirgilKeyPair(
            new VirgilKey($publicKeyHash, $publicKeyDER),
            new VirgilKey($publicKeyHash, $privateKeyDER)
        );
    }
}