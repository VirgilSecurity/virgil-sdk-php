<?php

namespace Virgil\SDK\Cryptography;


use Virgil\Crypto\VirgilHash;

class HashAlgorithm
{
    const DefaultType = VirgilHash::Algorithm_SHA256;

    const MD5 = VirgilHash::Algorithm_MD5;

    const SHA1 = VirgilHash::Algorithm_SHA1;

    const SHA224 = VirgilHash::Algorithm_SHA224;

    const SHA256 = VirgilHash::Algorithm_SHA256;

    const SHA384 = VirgilHash::Algorithm_SHA384;

    const SHA512 = VirgilHash::Algorithm_SHA512;
}