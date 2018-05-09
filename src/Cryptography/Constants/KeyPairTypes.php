<?php
namespace Virgil\Sdk\Cryptography\Constants;


use VirgilKeyPair as CryptoVirgilKeyPair;

/**
 * Class keeps list of key pair types constants.
 */
class KeyPairTypes
{
    const RSA256 = CryptoVirgilKeyPair::Type_RSA_256;

    const RSA512 = CryptoVirgilKeyPair::Type_RSA_512;

    const RSA1024 = CryptoVirgilKeyPair::Type_RSA_1024;

    const RSA2048 = CryptoVirgilKeyPair::Type_RSA_2048;

    const RSA3072 = CryptoVirgilKeyPair::Type_RSA_3072;

    const RSA4096 = CryptoVirgilKeyPair::Type_RSA_4096;

    const RSA8192 = CryptoVirgilKeyPair::Type_RSA_8192;

    const EC_SECP192R1 = CryptoVirgilKeyPair::Type_EC_SECP192R1;

    const EC_SECP224R1 = CryptoVirgilKeyPair::Type_EC_SECP224R1;

    const EC_SECP256R1 = CryptoVirgilKeyPair::Type_EC_SECP256R1;

    const EC_SECP384R1 = CryptoVirgilKeyPair::Type_EC_SECP384R1;

    const EC_SECP521R1 = CryptoVirgilKeyPair::Type_EC_SECP521R1;

    const EC_BP256R1 = CryptoVirgilKeyPair::Type_EC_BP256R1;

    const EC_BP384R1 = CryptoVirgilKeyPair::Type_EC_BP384R1;

    const EC_BP512R1 = CryptoVirgilKeyPair::Type_EC_BP512R1;

    const EC_SECP192K1 = CryptoVirgilKeyPair::Type_EC_SECP192K1;

    const EC_SECP224K1 = CryptoVirgilKeyPair::Type_EC_SECP224K1;

    const EC_SECP256K1 = CryptoVirgilKeyPair::Type_EC_SECP256K1;

    const EC_CURVE25519 = CryptoVirgilKeyPair::Type_EC_CURVE25519;

    const FAST_EC_X25519 = CryptoVirgilKeyPair::Type_FAST_EC_X25519;

    const FAST_EC_ED25519 = CryptoVirgilKeyPair::Type_FAST_EC_ED25519;
}
