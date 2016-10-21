<?php

namespace Virgil\SDK\Cryptography;


use Virgil\Crypto\VirgilKeyPair as LibraryKeyPair;

class VirgilCryptoType
{
    const DefaultType = LibraryKeyPair::Type_FAST_EC_ED25519;

    const RSA256 = LibraryKeyPair::Type_RSA_256;

    const RSA512 = LibraryKeyPair::Type_RSA_512;

    const RSA1024 = LibraryKeyPair::Type_RSA_1024;

    const RSA2048 = LibraryKeyPair::Type_RSA_2048;

    const RSA3072 = LibraryKeyPair::Type_RSA_3072;

    const RSA4096 = LibraryKeyPair::Type_RSA_4096;

    const RSA8192 = LibraryKeyPair::Type_RSA_8192;

    const EC_SECP192R1 = LibraryKeyPair::Type_EC_SECP192R1;

    const EC_SECP224R1 = LibraryKeyPair::Type_EC_SECP224R1;

    const EC_SECP256R1 = LibraryKeyPair::Type_EC_SECP256R1;

    const EC_SECP384R1 = LibraryKeyPair::Type_EC_SECP384R1;

    const EC_SECP521R1 = LibraryKeyPair::Type_EC_SECP521R1;

    const EC_BP256R1 = LibraryKeyPair::Type_EC_BP256R1;

    const EC_BP384R1 = LibraryKeyPair::Type_EC_BP384R1;

    const EC_BP512R1 = LibraryKeyPair::Type_EC_BP512R1;

    const EC_SECP192K1 = LibraryKeyPair::Type_EC_SECP192K1;

    const EC_SECP224K1 = LibraryKeyPair::Type_EC_SECP224K1;

    const EC_SECP256K1 = LibraryKeyPair::Type_EC_SECP256K1;

    const EC_CURVE25519 = LibraryKeyPair::Type_EC_CURVE25519;

    const FAST_EC_X25519 = LibraryKeyPair::Type_FAST_EC_X25519;

    const FAST_EC_ED25519 = LibraryKeyPair::Type_FAST_EC_ED25519;
}