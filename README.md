# PHP SDK Programming Guide

Welcome to the SDK Programming Guide for PHP language. This guide is a practical introduction to create apps that make use of Virgil Security features. The code examples in this guide are written in PHP. 

In this guide you will find code for every task you need to implement in order to create an application using Virgil Security. It also includes a description of the main classes and methods. The aim of this guide is to get you up and running quickly. You should be able to copy and paste the code provided into your own apps and use it with minimal changes.

## Table of Contents

* [Setting up your project](#setting-up-your-project)
* [User and App Credentials](#user-and-app-credentials)
* [Creating a Virgil Card](#creating-a-virgil-card)
* [Search for Virgil Cards](#search-for-virgil-cards)
* [Getting a Virgil Card](#getting-a-virgil-card)
* [Validating Virgil Cards](#validating-virgil-cards)
* [Revoking a Virgil Card](#revoking-a-virgil-card)
* [Operations with Crypto Keys](#operations-with-crypto-keys)
  * [Generate Keys](#generate-keys)
  * [Import and Export Keys](#import-and-export-keys)
* [Encryption and Decryption](#encryption-and-decryption)
* [Generating and Verifying Signatures](#generating-and-verifying-signatures)
* [Authenticated Encryption](#authenticated-encryption)
* [Fingerprint Generation](#fingerprint-generation)
* [Release Notes](#release-notes)

## Setting up your project

The Virgil SDK is provided as a package named *virgil/sdk*. The package is distributed via **composer** package management system.

### Target platforms

* PHP 5.6+.

### Installing the package

Install the latest version with
```
$ composer require virgil/sdk
```


## User and App Credentials

When you register an application on the Virgil developer's [dashboard](https://developer.virgilsecurity.com/dashboard), we provide you with an *appID*, *appKey* and *accessToken*.

* **appID** uniquely identifies your application in our services, it is also used to identify the Public key generated in a pair with *appKey*, for example: ```af6799a2f26376731abb9abf32b5f2ac0933013f42628498adb6b12702df1a87```
* **appKey** is a Private key that is used to perform creation and revocation of *Virgil Cards* (Public key) in Virgil services. Also the *appKey* can be used for cryptographic operations to take part in application logic. The *appKey* is generated at the time of creation application and has to be saved in secure place. 
* **accessToken** is a unique string value that provides an authenticated secure access to the Virgil services and is passed with each API call. The *accessToken* also allows the API to associate your app’s requests with your Virgil developer’s account. 

## Connecting to Virgil
Before you can use any Virgil services features in your app, you must first initialize ```VirgilClient``` class. You use the ```VirgilClient``` object to get access to Create, Revoke and Search for *Virgil Cards* (Public keys). 

### Initializing an API Client

To create an instance of *VirgilClient* class, just call its static method with your application's *accessToken* which you generated on developer's deshboard.


```php
<?php

use Virgil\SDK\Client\VirgilClient;

$client = VirgilClient::create("[YOUR_ACCESS_TOKEN_HERE]");
```

you can also customize initialization using your own parameters

```php
<?php

use Virgil\SDK\Client\VirgilClient;
use Virgil\SDK\Client\VirgilClientParams;

$parameters = new VirgilClientParams("[YOUR_ACCESS_TOKEN_HERE]");

$parameters->setCardsServiceAddress("https://cards.virgilsecurity.com");
$parameters->setReadCardsServiceAddress("https://cards-ro.virgilsecurity.com");
$parameters->setIdentityServiceAddress("https://identity.virgilsecurity.com");

$client = new VirgilClient($parameters);
```

### Initializing Crypto
The *VirgilCrypto* class provides cryptographic operations in applications, such as hashing, signature generation and verification, and encryption and decryption.


```php
<?php

use Virgil\SDK\Cryptography\VirgilCrypto;

$crypto = new VirgilCrypto();
```

## Creating a Virgil Card

A *Virgil Card* is the main entity of the Virgil services, it includes the information about the user and his public key. The *Virgil Card* identifies the user/device by one of his types. 

Collect an *appID* and *appKey* for your app. These parameters are required to create a Virgil Card in your app scope. 

```php
<?php

use Virgil\SDK\Buffer;

$appID = "[YOUR_APP_ID_HERE]";
$appKeyPassword = "[YOUR_APP_KEY_PASSWORD_HERE]";
$appKeyData = new Buffer(file_get_contents("[YOUR_APP_KEY_PATH_HERE]"));

$appKey = $crypto->importPrivateKey($appKeyData, $appKeyPassword);
```
Generate a new Public/Private keypair using *VirgilCrypto* class. 

```php
<?php

$aliceKeys = $crypto->generateKeys();
```
Prepare request
```php
<?php

use Virgil\SDK\Client\Requests\CreateCardRequest;

$exportedPublicKey = $crypto->exportPublicKey($aliceKeys->getPublicKey());
$createCardRequest = new CreateCardRequest("alice", "username", $exportedPublicKey);
```

then, use *RequestSigner* class to sign request with owner and app keys.

```php
<?php

use Virgil\SDK\Client\Requests\RequestSigner;

$requestSigner = new RequestSigner($crypto);

$requestSigner->selfSign($createCardRequest, $aliceKeys->getPrivateKey())
              ->authoritySign($createCardRequest, $appID, $appKey)
;
```
Publish a Virgil Card
```php
<?php

$aliceCard = $client->createCard($createCardRequest);
```

## Search for Virgil Cards
Performs the `Virgil Card's` search by criteria request:
- the *IdentityType* is optional and specifies the *IdentityType* of a `Virgil Cards` to be found;
- the *Scope* optional request parameter specifies the scope to perform search on. Either 'global' or 'application'. The default value is 'application';
- There is need append one *Identity* at least or set all of them.
```php
<?php

use Virgil\SDK\Client\VirgilClient;

use Virgil\SDK\Client\Requests\SearchCardRequest;

$client = VirgilClient::create("[YOUR_ACCESS_TOKEN_HERE]");
 
$searchCardRequest = new SearchCardRequest();
$searchCardRequest->appendIdentity("alice")
                  ->appendIdentity("bob");

$cards = $client->searchCards($searchCardRequest);
```

## Getting a Virgil Card
Gets a `Virgil Card` by ID.

```php
<?php

use Virgil\SDK\Client\VirgilClient;

$client = VirgilClient::create("[YOUR_ACCESS_TOKEN_HERE]"); 
$card = $client->getCard("[YOUR_CARD_ID_HERE]");
```

## Validating Virgil Cards
This sample uses *built-in* ```CardValidator``` to validate cards. By default ```CardValidator``` validates only *Cards Service* signature. 

```php
<?php

use Virgil\SDK\Buffer;

use Virgil\SDK\Cryptography\VirgilCrypto;

use Virgil\SDK\Client\VirgilClient;

use Virgil\SDK\Client\Requests\SearchCardRequest;

use Virgil\SDK\Client\Validator\CardValidator;
use Virgil\SDK\Client\Validator\CardValidationException;

// Initialize crypto API
$crypto = new VirgilCrypto();

$validator = new CardValidator($crypto);

// Your can also add another Public Key for verification.
//$publicKey = $crypto->importPublicKey(new Buffer("[HERE_VERIFIER_PUBLIC_KEY]"));
//$validator->addVerifier("[HERE_VERIFIER_CARD_ID]", $publicKey);

// Initialize service client
$client = VirgilClient::create("[YOUR_ACCESS_TOKEN_HERE]");
$client->setCardValidator($validator);

try
{
    $searchCardRequest = new SearchCardRequest();
    $searchCardRequest->setIdentities(["alice", "bob"]);
    
    $cards = $client->searchCards($searchCardRequest);
}
catch (CardValidationException $exception)
{

}
```

## Revoking a Virgil Card
Initialize required components.
```php
<?php

use Virgil\SDK\Cryptography\VirgilCrypto;
use Virgil\SDK\Client\VirgilClient;
use Virgil\SDK\Client\Requests\RequestSigner;

$client = VirgilClient::create("[YOUR_ACCESS_TOKEN_HERE]");
$crypto = new VirgilCrypto();

$requestSigner = new RequestSigner($crypto);
```

Collect *App* credentials 
```php
<?php

use Virgil\SDK\Buffer;

$appID = "[YOUR_APP_ID_HERE]";
$appPrivateKeyPassword = "[YOUR_APP_KEY_PASSWORD_HERE]";
$appPrivateKeyData = new Buffer(file_get_contents("[YOUR_APP_KEY_PATH_HERE]"));

$appPrivateKey = $crypto->importPrivateKey($appPrivateKeyData, $appPrivateKeyPassword);
```

Prepare revocation request
```php
<?php

use Virgil\SDK\Client\Requests\RevokeCardRequest;
use Virgil\SDK\Client\Requests\Constants\RevocationReasons;

$cardId = "[YOUR_CARD_ID_HERE]";

$revokeRequest = new RevokeCardRequest($cardId, RevocationReasons::TYPE_UNSPECIFIED);
$requestSigner->authoritySign($revokeRequest, $appID, $appPrivateKey);

$client->revokeCard($revokeRequest);
```

## Operations with Crypto Keys

### Generate Keys
The following code sample illustrates keypair generation. The default algorithm is ed25519

```php
<?php

use Virgil\SDK\Cryptography\VirgilCrypto;

$crypto = new VirgilCrypto();
$aliceKeys = $crypto->generateKeys();
```

### Import and Export Keys
You can export and import your Public/Private keys to/from supported wire representation.

To export Public/Private keys, simply call one of the Export methods:

```php
<?php

$exportedPrivateKey = $crypto->exportPrivateKey($aliceKeys->getPrivateKey());
$exportedPublicKey = $crypto->exportPublicKey($aliceKeys->getPublicKey());
```
 
 To import Public/Private keys, simply call one of the Import methods:
 
 ```php
<?php

$privateKey = $crypto->importPrivateKey($exportedPrivateKey);
$publicKey = $crypto->importPublicKey($exportedPublicKey);
```

## Encryption and Decryption

Initialize Crypto API and generate keypair.
```php
<?php

use Virgil\SDK\Cryptography\VirgilCrypto;

$crypto = new VirgilCrypto();
$aliceKeys = $crypto->generateKeys();
```

### Encrypt Data
Data encryption using ECIES scheme with AES-GCM. You can encrypt either data string or stream.
There also can be more than one recipient

 *Data string*
```php
<?php

$plaintext = "Hello Bob!";
$encryptedData = $crypto->encrypt($plaintext, [$aliceKeys->getPublicKey()]);
```

 *Stream*
```php
<?php
 
$source = fopen('php://memory', 'r+');
$sin = fopen('php://memory', 'r+');

$crypto->encryptStream($source, $sin, [$aliceKeys->getPublicKey()]);
```

### Decrypt Data
You can decrypt either stream or a data string using your private key

 *Data string*
```php
<?php

$crypto->decrypt($encryptedData, $aliceKeys->getPrivateKey());
```

 *Stream*
```php
<?php

$source = fopen('php://memory', 'r+');
$sin = fopen('php://memory', 'r+');
  
$crypto->decryptStream($source, $sin, $aliceKeys->getPrivateKey());
```

## Generating and Verifying Signatures
This section walks you through the steps necessary to use the *VirgilCrypto* to generate a digital signature for data and to verify that a signature is authentic. 

Generate a new Public/Private keypair and *data* to be signed.

```php
<?php

use Virgil\SDK\Cryptography\VirgilCrypto;

$crypto = new VirgilCrypto();
$aliceKeys = $crypto->generateKeys();

// The data to be signed with alice's Private key
$data = "Hello Bob, How are you?";
```

### Generating a Signature

Sign the SHA-384 fingerprint of either stream or a data string using your private key. To generate the signature, simply call one of the sign methods:

*Data string*
```php
<?php

$signature = $crypto->sign($data, $aliceKeys->getPrivateKey());
```
*Stream*
```php
<?php

$source = fopen('file://[YOUR_FILE_PATH_HERE]', 'r+');

$crypto->signStream($source, $aliceKeys->getPrivateKey());
```
### Verifying a Signature

Verify the signature of the SHA-384 fingerprint of either stream or a data string using Public key. The signature can now be verified by calling the verify method:

*Data string*

```php
<?php

$isValid = $crypto->verify($data, $signature, $aliceKeys->getPublicKey());
 ```
 
 *Stream*
 
 ```php
<?php

$source = fopen('file://[YOUR_FILE_PATH_HERE]', 'r+');
$isValid = $crypto->verifyStream($source, $signature, $aliceKeys->getPublicKey());
```
## Authenticated Encryption
Authenticated Encryption provides both data confidentiality and data integrity assurances to the information being protected.

```php
<?php

use Virgil\SDK\Cryptography\VirgilCrypto;

$crypto = new VirgilCrypto();
 
$alice = $crypto->generateKeys();
$bob = $crypto->generateKeys();

// The data to be signed with alice's Private key
$data = "Hello Bob, How are you?";
```

### Sign then Encrypt
```php
<?php

$encryptedData = $crypto->signThenEncrypt($data, $alice->getPrivateKey(), [$bob->getPublicKey()]);
```

### Decrypt then Verify
```php
<?php

$decryptedData = $crypto->decryptThenVerify($encryptedData, $bob->getPrivateKey(), $alice->getPublicKey());
```

## Fingerprint Generation
The default Fingerprint algorithm is SHA-256.
```php
<?php

use Virgil\SDK\Cryptography\VirgilCrypto;

use Virgil\SDK\Buffer;

$crypto = new VirgilCrypto();
$content = new Buffer('content_string');
$fingerprint = $crypto->calculateFingerprint($content);
```

## Release Notes
 - Please read the latest note here: [https://github.com/VirgilSecurity/virgil-sdk-php/releases](https://github.com/VirgilSecurity/virgil-sdk-php/releases)
