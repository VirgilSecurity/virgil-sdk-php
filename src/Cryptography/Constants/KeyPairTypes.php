<?php
namespace Virgil\Sdk\Cryptography\Constants;


use Virgil\Crypto\VirgilKeyPair;

/**
 * Class keeps list of key pair types constants.
 */
class KeyPairTypes
{
    const RSA256 = VirgilKeyPair::Type_RSA_256;

    const RSA512 = VirgilKeyPair::Type_RSA_512;

    const RSA1024 = VirgilKeyPair::Type_RSA_1024;

    const RSA2048 = VirgilKeyPair::Type_RSA_2048;

    const RSA3072 = VirgilKeyPair::Type_RSA_3072;

    const RSA4096 = VirgilKeyPair::Type_RSA_4096;

    const RSA8192 = VirgilKeyPair::Type_RSA_8192;

    const EC_SECP192R1 = VirgilKeyPair::Type_EC_SECP192R1;

    const EC_SECP224R1 = VirgilKeyPair::Type_EC_SECP224R1;

    const EC_SECP256R1 = VirgilKeyPair::Type_EC_SECP256R1;

    const EC_SECP384R1 = VirgilKeyPair::Type_EC_SECP384R1;

    const EC_SECP521R1 = VirgilKeyPair::Type_EC_SECP521R1;

    const EC_BP256R1 = VirgilKeyPair::Type_EC_BP256R1;

    const EC_BP384R1 = VirgilKeyPair::Type_EC_BP384R1;

    const EC_BP512R1 = VirgilKeyPair::Type_EC_BP512R1;

    const EC_SECP192K1 = VirgilKeyPair::Type_EC_SECP192K1;

    const EC_SECP224K1 = VirgilKeyPair::Type_EC_SECP224K1;

    const EC_SECP256K1 = VirgilKeyPair::Type_EC_SECP256K1;

    const EC_CURVE25519 = VirgilKeyPair::Type_EC_CURVE25519;

    const FAST_EC_X25519 = VirgilKeyPair::Type_FAST_EC_X25519;

    const FAST_EC_ED25519 = VirgilKeyPair::Type_FAST_EC_ED25519;
}
