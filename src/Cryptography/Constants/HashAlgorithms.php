<?php
namespace Virgil\Sdk\Cryptography\Constants;


use VirgilHash as CryptoVirgilHash;

/**
 * Class keeps list of hash algorithms constants.
 */
class HashAlgorithms
{
    const MD5 = CryptoVirgilHash::Algorithm_MD5;

    const SHA1 = CryptoVirgilHash::Algorithm_SHA1;

    const SHA224 = CryptoVirgilHash::Algorithm_SHA224;

    const SHA256 = CryptoVirgilHash::Algorithm_SHA256;

    const SHA384 = CryptoVirgilHash::Algorithm_SHA384;

    const SHA512 = CryptoVirgilHash::Algorithm_SHA512;
}
