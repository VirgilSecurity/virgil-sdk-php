# Virgil Security PHP SDK
[![Build Status](https://travis-ci.com/VirgilSecurity/virgil-sdk-php.png?branch=master)](https://travis-ci.com/VirgilSecurity/virgil-sdk-php)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/virgil/sdk.svg?style=flat-square)](https://packagist.org/packages/virgil/sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/virgil/sdk.svg?style=flat-square)](https://packagist.org/packages/virgil/sdk.svg)
[![GitHub license](https://img.shields.io/badge/license-BSD%203--Clause-blue.svg)](https://github.com/VirgilSecurity/virgil/blob/master/LICENSE)

[Introduction](#installation) | [SDK Features](#sdk-features) | [Installation](#installation) | [Usage Examples](#usage-examples) | [Docs](#docs) | [Support](#support) | [SDK V4](#sdk-v4)


<a href="https://virgilsecurity.com"><img width="230px" src="logo.png" align="left" hspace="10" vspace="6"></a>[Virgil Security](https://virgilsecurity.com) provides a set of APIs for adding security to any application. In a few simple steps you can encrypt communication, securely store data, provide passwordless login, and ensure data integrity.

The Virgil SDK allows developers to get up and running with Virgil API quickly and add full end-to-end security to their existing digital solutions to become HIPAA and GDPR compliant and more.

## SDK Features
- communicate with [Virgil Cards Service][_cards_service]
- manage users' Public Keys
- store private keys in secure local storage
- use Virgil [Crypto library][_virgil_crypto]
- use your own Crypto



## Installation

The Virgil SDK is provided as a package named [*virgil/sdk*](https://packagist.org/packages/virgil/sdk). The package is distributed via [Composer package](https://getcomposer.org/doc/) management system.

The package is available for PHP version 5.6 and newer.

Installing the package using Package Manager Console:

```bash
composer require virgil/sdk
```

### Crypto library notice

In order to support cipher operations upon your data there is must be installed crypto library also. We supply Virgil  SDK with own implementation of cypto intefaces that can be easyly used by everyone. Just require it as a package:

```bash
composer require virgil/crypto
```

Be aware this package requires installed php virgil crypto extension *ext-virgil_crypto_php*.

Also there is avaliable [setup your own Crypto library][_own_crypto] inside of the SDK

### Using external crypto library (c++)

If you decide to use *virgil/crypto* package there is needs to install php virgil crypto extension
*ext-virgil_crypto_php* as one of dependency otherwise you will get the requested PHP extension virgil_crypto_php
is missing from your system error during composer install.
Download proper extension package for your platform from [cdn](https://cdn.virgilsecurity.com/virgil-crypto/php/)
like **virgil-crypto-2.3.0-php-5.6-linux-x86_64.tgz** (highly recommended using latest version)
and install. Unfortunately PHP extensions installation is out of this topic but you can find a lot of public information about it.

## Usage Examples

Before starting practicing with the usage examples be sure that the SDK is configured. Check out our [SDK configuration guides][_configure_sdk] for more information.

#### Generate and publish user's Cards with Public Keys inside on Cards Service
Use the following lines of code to create and publish a user's Card with Public Key inside on Virgil Cards Service:

```php
use Virgil\CryptoImpl\VirgilCrypto;
use Virgil\Sdk\CardParams;

$crypto = new VirgilCrypto();

// generate a key pair
$keyPair = $crypto->generateKeys();

// save a private key into key storage
$privateKeyStorage->store($keyPair->getPrivateKey(), "Alice");

// publish user's on the Cards Service
$card = $cardManager->publishCard(
    CardParams::create(
        [
            CardParams::PublicKey  => $keyPair->getPublicKey(),
            CardParams::PrivateKey => $keyPair->getPrivateKey(),
        ]
    )
);
```

#### Sign then encrypt data

Virgil SDK lets you use a user's Private key and his or her Cards to sign, then encrypt any kind of data.

In the following example, we load a Private Key from a customized Key Storage and get recipient's Card from the Virgil Cards Services. Recipient's Card contains a Public Key on which we will encrypt the data and verify a signature.

```php
use Virgil\CryptoImpl;
use Virgil\Sdk;

// prepare a message
$dataToEncrypt = 'Hello, Bob!';

// prepare a user's private key
$alicePrivateKeyEntry = $privateKeyStorage->load('Alice');

// using cardManager search for Bob's cards on Cards Service
$cads = $cardManager->searchCards('Bob');

$bobRelevantCardsPublicKeys = array_map(
    function (Sdk\Card $card) {
        return $card->getPublicKey();
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

#### Decrypt then verify data
Once the Users receive the signed and encrypted message, they can decrypt it with their own Private Key and verify signature with a Sender's Card:

```php
use Virgil\CryptoImpl;
use Virgil\Sdk;

// prepare a user's private key
$bobPrivateKeyEntry = $privateKeyStorage->load('Bob');

// using cardManager search for Alice's cards on Cards Service
$cards = $cardManager->searchCards('Alice');

// using cardManager search for Alice's cards on Cards Service
$aliceRelevantCardsPublicKeys = array_map(
    function (Sdk\Card $card) {
        return $card->getPublicKey();
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

## Docs
Virgil Security has a powerful set of APIs, and the documentation below can get you started today.

In order to use the Virgil SDK with your application, you will need to first configure your application. By default, the SDK will attempt to look for Virgil-specific settings in your application but you can change it during SDK configuration.

* [Configure the SDK][_configure_sdk] documentation
  * [Setup authentication][_setup_authentication] to make API calls to Virgil Services
  * [Setup Card Manager][_card_manager] to manage user's Public Keys
  * [Setup Card Verifier][_card_verifier] to verify signatures inside of user's Card
  * [Setup Key storage][_key_storage] to store Private Keys
  * [Setup your own Crypto library][_own_crypto] inside of the SDK
* [More usage examples][_more_examples]
  * [Create & publish a Card][_create_card] that has a Public Key on Virgil Cards Service
  * [Search user's Card by user's identity][_search_card]
  * [Get user's Card by its ID][_get_card]
  * [Use Card for crypto operations][_use_card]
* [Reference API][_reference_api]


## License

This library is released under the [3-clause BSD License](LICENSE.md).

## Support
Our developer support team is here to help you. Find out more information on our [Help Center](https://help.virgilsecurity.com/).

You can find us on [Twitter](https://twitter.com/VirgilSecurity) or send us email support@VirgilSecurity.com.

Also, get extra help from our support team on [Slack](https://virgilsecurity.slack.com/join/shared_invite/enQtMjg4MDE4ODM3ODA4LTc2OWQwOTQ3YjNhNTQ0ZjJiZDc2NjkzYjYxNTI0YzhmNTY2ZDliMGJjYWQ5YmZiOGU5ZWEzNmJiMWZhYWVmYTM).

## SDK-V4

https://github.com/VirgilSecurity/virgil-sdk-php/tree/v4

[_virgil_crypto]: https://github.com/VirgilSecurity/virgil-sdk-crypto-php
[_cards_service]: https://developer.virgilsecurity.com/docs/api-reference/card-service/v5
[_use_card]: https://developer.virgilsecurity.com/docs/php/how-to/public-key-management/v5/use-card-for-crypto-operation
[_get_card]: https://developer.virgilsecurity.com/docs/php/how-to/public-key-management/v5/get-card
[_search_card]: https://developer.virgilsecurity.com/docs/php/how-to/public-key-management/v5/search-card
[_create_card]: https://developer.virgilsecurity.com/docs/php/how-to/public-key-management/v5/create-card
[_own_crypto]: https://developer.virgilsecurity.com/docs/php/how-to/setup/v5/setup-own-crypto-library
[_key_storage]: https://developer.virgilsecurity.com/docs/php/how-to/setup/v5/setup-key-storage
[_card_verifier]: https://developer.virgilsecurity.com/docs/php/how-to/setup/v5/setup-card-verifier
[_card_manager]: https://developer.virgilsecurity.com/docs/php/how-to/setup/v5/setup-card-manager
[_setup_authentication]: https://developer.virgilsecurity.com/docs/php/how-to/setup/v5/setup-authentication
[_reference_api]: https://developer.virgilsecurity.com/docs/api-reference
[_configure_sdk]: https://developer.virgilsecurity.com/docs/how-to#sdk-configuration
[_more_examples]: https://developer.virgilsecurity.com/docs/how-to#public-key-management
