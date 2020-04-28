# Virgil Core SDK PHP
[![Build Status](https://travis-ci.com/VirgilSecurity/virgil-sdk-php.png?branch=master)](https://travis-ci.com/VirgilSecurity/virgil-sdk-php)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/virgil/sdk.svg?style=flat-square)](https://packagist.org/packages/virgil/sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/virgil/sdk.svg?style=flat-square)](https://packagist.org/packages/virgil/sdk.svg)
[![GitHub license](https://img.shields.io/badge/license-BSD%203--Clause-blue.svg)](https://github.com/VirgilSecurity/virgil/blob/master/LICENSE)

[Introduction](#introduction) | [SDK Features](#sdk-features) | [Installation](#installation) | [Configure SDK](#configure-sdk) | [Usage Examples](#usage-examples) | [Docs](#docs) | [Support](#support)

## Introduction

<a href="https://developer.virgilsecurity.com/docs"><img width="230px" src="https://cdn.virgilsecurity.com/assets/images/github/logos/virgil-logo-red.png" align="left" hspace="10" vspace="6"></a> [Virgil Security](https://virgilsecurity.com) provides a set of APIs for adding security to any application. In a few simple steps you can encrypt communications, securely store data, and ensure data integrity. Virgil Security products are available for desktop, embedded (IoT), mobile, cloud, and web applications in a variety of modern programming languages.

The Virgil Core SDK is a low-level library that allows developers to get up and running with [Virgil Cards Service API](https://developer.virgilsecurity.com/docs/platform/api-reference/cards-service/) quickly and add end-to-end security to their new or existing digital solutions.

In case you need additional security functionality for multi-device support, group chats and more, try our high-level [Virgil E3Kit framework](https://github.com/VirgilSecurity/awesome-virgil#E3Kit).

## SDK Features
- Communicate with [Virgil Cards Service](https://developer.virgilsecurity.com/docs/platform/api-reference/cards-service/)
- Manage users' public keys
- Encrypt, sign, decrypt and verify data
- Store private keys in secure local storage
- Use [Virgil Crypto Library](https://github.com/VirgilSecurity/virgil-crypto-php)
- Use your own crypto library

## Installation

The Virgil Core SDK is provided as a package named [*virgil/sdk*](https://packagist.org/packages/virgil/sdk). The package is distributed via [Composer package](https://getcomposer.org/doc/) management system.

The package is available for PHP version 5.6 and newer.

Installing the package using Package Manager Console:

```bash
composer require virgil/sdk
```

### Crypto library notice

In order to support crypto operations, you'll also need to install a crypto library. We supply Virgil Core SDK with our own implementation of cypto intefaces that can be easily used by everyone. Just require it as a package:

```bash
composer require virgil/crypto
```

Be aware that this package requires installed php virgil crypto extension *ext-virgil_crypto_php*.

### Using external crypto library (c++)

If you decide to use *virgil/crypto* package, there is needs to install php virgil crypto extension *ext-virgil_crypto_php* as one of the dependencies, otherwise you will get the "requested PHP extension virgil_crypto_php is missing from your system" error during composer install.

Download proper extension package for your platform from [cdn](https://cdn.virgilsecurity.com/virgil-crypto/php/) like **virgil-crypto-2.3.0-php-5.6-linux-x86_64.tgz** (highly recommended using latest version), and install it.

## Configure SDK

This section contains guides on how to set up Virgil Core SDK modules for authenticating users, managing Virgil Cards and storing private keys.

### Set up authentication

Set up user authentication with tokens that are based on the [JSON Web Token standard](https://jwt.io/) with some Virgil modifications.

In order to make calls to Virgil Services (for example, to publish user's Card on Virgil Cards Service), you need to have a JSON Web Token ("JWT") that contains the user's `identity`, which is a string that uniquely identifies each user in your application.

Credentials that you'll need:

|Parameter|Description|
|--- |--- |
|App ID|ID of your Application at [Virgil Dashboard](https://dashboard.virgilsecurity.com)|
|App Key ID|A unique string value that identifies your account at the Virgil developer portal|
|App Key|A Private Key that is used to sign API calls to Virgil Services. For security, you will only be shown the App Key when the key is created. Don't forget to save it in a secure location for the next step|

#### Set up JWT provider on Client side

Use these lines of code to specify which JWT generation source you prefer to use in your project:

```php
use Virgil\Sdk\Web\Authorization\CallbackJwtProvider;
use Virgil\Sdk\Web\Authorization\TokenContext;

$authenticatedQueryToServerSide = function (TokenContext $context){
    // Get generated token from server-side
    return "eyJraWQiOiI3MGI0NDdlMzIxZjNhMGZkIiwidHlwIjoiSldUIiwiYWxnIjoiVkVEUzUxMiIsImN0eSI6InZpcmdpbC1qd3Q7dj0xIn0.eyJleHAiOjE1MTg2OTg5MTcsImlzcyI6InZpcmdpbC1iZTAwZTEwZTRlMWY0YmY1OGY5YjRkYzg1ZDc5Yzc3YSIsInN1YiI6ImlkZW50aXR5LUFsaWNlIiwiaWF0IjoxNTE4NjEyNTE3fQ.MFEwDQYJYIZIAWUDBAIDBQAEQP4Yo3yjmt8WWJ5mqs3Yrqc_VzG6nBtrW2KIjP-kxiIJL_7Wv0pqty7PDbDoGhkX8CJa6UOdyn3rBWRvMK7p7Ak";
};

// Setup AccessTokenProvider
$accessTokenProvider = new CallbackJwtProvider($authenticatedQueryToServerSide);
```

#### Generate JWT on Server side

Next, you'll need to set up the `JwtGenerator` and generate a JWT using the Virgil SDK.

Here is an example of how to generate a JWT:

```php
use Virgil\CryptoImpl\VirgilAccessTokenSigner;
use Virgil\CryptoImpl\VirgilCrypto;

use Virgil\Sdk\Web\Authorization\JwtGenerator;

// App Key (you got this Key at Virgil Dashboard)
$privateKeyStr = "MIGhMF0GCSqGSIb3DQEFDTBQMC8GCSqGSIb3DQEFDDAiBBC7Sg/DbNzhJ/uakTva";
$appKeyData = base64_decode($privateKeyStr);

// Crypto library imports a private key into a necessary format
$crypto = new VirgilCrypto();
$privateKey = $crypto->importPrivateKey($appKeyData);

// initialize accessTokenSigner that signs users JWTs
$accessTokenSigner = new VirgilAccessTokenSigner();

// use your App Credentials you got at Virgil Dashboard:
$appId = "be00e10e4e1f4bf58f9b4dc85d79c77a"; // App ID
$appKeyId = "70b447e321f3a0fd";              // App Key ID
$ttl = 3600; // 1 hour (JWT's lifetime)

// setup JWT generator with necessary parameters:
$jwtGenerator = new JwtGenerator($privateKey, $appKeyId, $accessTokenSigner, $appId, $ttl);

// generate JWT for a user
// remember that you must provide each user with his unique JWT
// each JWT contains unique user's identity (in this case - Alice)
// identity can be any value: name, email, some id etc.
$identity = "Alice";
$token = $jwtGenerator->generateToken($identity);

// as result you get users JWT, it looks like this: "eyJraWQiOiI3MGI0NDdlMzIxZjNhMGZkIiwidHlwIjoiSldUIiwiYWxnIjoiVkVEUzUxMiIsImN0eSI6InZpcmdpbC1qd3Q7dj0xIn0.eyJleHAiOjE1MTg2OTg5MTcsImlzcyI6InZpcmdpbC1iZTAwZTEwZTRlMWY0YmY1OGY5YjRkYzg1ZDc5Yzc3YSIsInN1YiI6ImlkZW50aXR5LUFsaWNlIiwiaWF0IjoxNTE4NjEyNTE3fQ.MFEwDQYJYIZIAWUDBAIDBQAEQP4Yo3yjmt8WWJ5mqs3Yrqc_VzG6nBtrW2KIjP-kxiIJL_7Wv0pqty7PDbDoGhkX8CJa6UOdyn3rBWRvMK7p7Ak"
// you can provide users with JWT at registration or authorization steps
// Send a JWT to client-side
$token->__toString();
```

For this subsection we've created a sample backend that demonstrates how you can set up your backend to generate the JWTs. To set up and run the sample backend locally, head over to your GitHub repo of choice:

[Node.js](https://github.com/VirgilSecurity/sample-backend-nodejs) | [Golang](https://github.com/VirgilSecurity/sample-backend-go) | [PHP](https://github.com/VirgilSecurity/sample-backend-php) | [Java](https://github.com/VirgilSecurity/sample-backend-java) | [Python](https://github.com/VirgilSecurity/virgil-sdk-python/tree/master#sample-backend-for-jwt-generation)
 and follow the instructions in README.
 
### Set up Card Verifier

Virgil Card Verifier helps you automatically verify signatures of a user's Card, for example when you get a Card from Virgil Cards Service.

By default, `VirgilCardVerifier` verifies only two signatures - those of a Card owner and Virgil Cards Service.

Set up `VirgilCardVerifier` with the following lines of code:

```php
use Virgil\CryptoImpl\VirgilCardCrypto;
use Virgil\CryptoImpl\VirgilCrypto;

use Virgil\Sdk\Verification\VerifierCredentials;
use Virgil\Sdk\Verification\VirgilCardVerifier;
use Virgil\Sdk\Verification\Whitelist;

// initialize Crypto library
$crypto = new VirgilCrypto();
$cardCrypto = new VirgilCardCrypto();

$publicKey = $crypto->importPublicKey("EXPORTED_PUBLIC_KEY");

$yourBackendVerifierCredentials = new VerifierCredentials("YOUR_BACKEND", $publicKey);

$yourBackendWhitelist = new Whitelist([$yourBackendVerifierCredentials]);

$cardVerifier = new VirgilCardVerifier($cardCrypto, true, true, $yourBackendWhitelist);

```

### Set up Card Manager

This subsection shows how to set up a Card Manager module to help you manage users' public keys.

With Card Manager you can:
- specify an access Token (JWT) Provider.
- specify a Card Verifier used to verify signatures of your users, your App Server, Virgil Services (optional).

Use the following lines of code to set up the Card Manager:

```php
use Virgil\Sdk\CardManager;
use Virgil\Sdk\Verification\VirgilCardVerifier;

$virgilCardVerifier = new VirgilCardVerifier($cardCrypto, true, true);

// initialize cardManager and specify accessTokenProvider, cardVerifier
$cardManager = new CardManager($cardCrypto, $virgilAccessTokenProvider, $virgilCardVerifier);
```

### Set up Key Storage for private keys

This subsection shows how to set up a `VSSKeyStorage` using Virgil SDK in order to save private keys after their generation.

Here is an example of how to set up the `VSSKeyStorage` class:

```php
use Virgil\CryptoImpl\VirgilCrypto;
use Virgil\CryptoImpl\VirgilPrivateKeyExporter;

use Virgil\Sdk\Storage\PrivateKeyStorage;

// initialize Crypto library
$crypto = new VirgilCrypto();
// Generate some private key
$keypair = $crypto->generateKeys();
$privateKey = $keypair->getPrivateKey();

// Setup PrivateKeyStorage
$exporter = new VirgilPrivateKeyExporter("passw0rd");
$privateKeyStorage = new PrivateKeyStorage($exporter, "~/keys/");

// Store a private key with a name, for example Alice
$privateKeyStorage->store($privateKey, "Alice");

// To load Alice private key use the following code lines:
$keyEntry = $privateKeyStorage->load("Alice");

// Delete a private key
$privateKeyStorage->delete("Alice");
```


## Usage Examples

Before you start practicing with the usage examples, make sure that the SDK is configured. See the [Configure SDK](#configure-sdk) section for more information.

### Generate and publish Virgil Cards at Cards Service

Use the following lines of code to create a user's Card with a public key inside and publish it at Virgil Cards Service:

```php
use Virgil\CryptoImpl\VirgilCrypto;
use Virgil\Sdk\CardParams;

$crypto = new VirgilCrypto();

// generate a key pair
$keyPair = $crypto->generateKeys();

// save Alice private key into key storage
$privateKeyStorage->store($keyPair->getPrivateKey(), "Alice");

// create and publish user's card with identity Alice on the Cards Service
$card = $cardManager->publishCard(
    CardParams::create(
        [
            CardParams::PublicKey  => $keyPair->getPublicKey(),
            CardParams::PrivateKey => $keyPair->getPrivateKey(),
        ]
    )
);
```

### Sign then encrypt data

Virgil Core SDK allows you to use a user's private key and their Virgil Cards to sign and encrypt any kind of data.

In the following example, we load a private key from a customized key storage and get recipient's Card from the Virgil Cards Service. Recipient's Card contains a public key which we will use to encrypt the data and verify a signature.

```php
use Virgil\CryptoImpl;
use Virgil\Sdk;

// prepare a message
$dataToEncrypt = 'Hello, Bob!';

// load a private key from a device storage
$alicePrivateKeyEntry = $privateKeyStorage->load('Alice');

// using cardManager search for Bob's cards on Cards Service
$cards = $cardManager->searchCards('Bob');

$bobRelevantCardsPublicKeys = array_map(
    function (Virgil\Sdk\Card $cards) {
        return $cards->getPublicKey();
    },
    $cads
);


// sign a message with a private key then encrypt using Bob's public keys
$encryptedData = $crypto->signThenEncrypt(
    $dataToEncrypt,
    $alicePrivateKeyEntry->getPrivateKey(),
    $bobRelevantCardsPublicKeys
);
```

### Decrypt data and verify signature

Once the user receives the signed and encrypted message, they can decrypt it with their own private key and verify the signature with the sender's Card:

```php
use Virgil\CryptoImpl;
use Virgil\Sdk;

// load a private key from a device storage
$bobPrivateKeyEntry = $privateKeyStorage->load('Bob');

// using cardManager search for Alice's cards on Cards Service
$cards = $cardManager->searchCards('Alice');

$aliceRelevantCardsPublicKeys = array_map(
    function (Virgil\Sdk\Card $cards) {
        return $cards->getPublicKey();
    },
    $cads
);

// decrypt with a private key and verify using one of Alice's public keys
$decryptedData = $crypto->decryptThenVerify(
    $encryptedData,
    $bobPrivateKeyEntry->getPrivateKey(),
    $aliceRelevantCardsPublicKeys
);
```

### Get Card by its ID

Use the following lines of code to get a user's card from Virgil Cloud by its ID:

```php
// using cardManager get a user's card from the Cards Service
$card = $cardManager->getCard("f4bf9f7fcbedaba0392f108c59d8f4a38b3838efb64877380171b54475c2ade8");
```

### Get Card by user's identity

For a single user, use the following lines of code to get a user's Card by a user's `identity`:

```php
// using cardManager search for user's cards on Cards Service
$cards = $cardManager->searchCards("Bob");
```

## Docs

Virgil Security has a powerful set of APIs, and the [Developer Documentation](https://developer.virgilsecurity.com/) can get you started today.

## License

This library is released under the [3-clause BSD License](LICENSE).

## Support

Our developer support team is here to help you. Find out more information on our [Help Center](https://help.virgilsecurity.com/).

You can find us on [Twitter](https://twitter.com/VirgilSecurity) or send us email support@VirgilSecurity.com.

Also, get extra help from our support team on [Slack](https://virgilsecurity.com/join-community).
