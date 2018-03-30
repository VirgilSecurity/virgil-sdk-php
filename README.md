# Virgil Security PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/virgil/sdk.svg?style=flat-square)](https://packagist.org/packages/virgil/sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/virgil/sdk.svg?style=flat-square)](https://packagist.org/packages/virgil/sdk.svg)
[![GitHub license](https://img.shields.io/badge/license-BSD%203--Clause-blue.svg)](https://github.com/VirgilSecurity/virgil/blob/master/LICENSE)

[Introduction](#introduction) | [SDK Features](#sdk-features) | [Installation](#installation) | [Initialization](#initialization) | [Usage Examples](#usage-examples) | [Docs](#docs) | [Support](#support)


## Introduction

<a href="https://developer.virgilsecurity.com/docs"><img width="230px" src="https://cdn.virgilsecurity.com/assets/images/github/logos/virgil-logo-red.png" align="left" hspace="10" vspace="6"></a> [Virgil Security](https://virgilsecurity.com) provides a set of APIs for adding security to any application. In a few simple steps you can encrypt communication, securely store data, provide passwordless login, and ensure data integrity.

The Virgil SDK allows developers to get up and running with Virgil API quickly and add full end-to-end security to their existing digital solutions to become HIPAA and GDPR compliant and more.

## SDK Features
- communicate with [Virgil Cards Service][_cards_service]
- manage users' Public Keys
- store private keys in secure local storage
- use Virgil [Crypto library][_virgil_crypto]

## Installation

The Virgil SDK is provided as a package named *virgil/sdk*. The package is distributed via **composer** package management system.

To install package, use the command below:

1. Go to the your project root directory.
2. Run

```
$ composer require virgil/sdk
```

You need to install php virgil crypto extension *ext-virgil_crypto_php* as one of dependency otherwise you will get the requested PHP extension virgil_crypto_php is missing from your system error during composer install.

* Download proper extension package for your platform from [cdn](https://cdn.virgilsecurity.com/virgil-crypto/php/) like **virgil-crypto-2.0.4-php-5.6-linux-x86_64.tgz** (highly recommended using latest version).
 * Type following command to unpack extension in terminal:

 ```
 $ tar -xvzf virgil-crypto-2.0.4-php-5.6-linux-x86_64.tgz
 ```

 * Place unpacked **virgil_crypto_php.so** under php extension path.
 * Add virgil extension to your **php.ini** configuration file like **extension = virgil_crypto_php.so**.

 ```
 $ echo "extension=virgil_crypto_php.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
 ```

All necessary information about where **php.ini** or **extension_dir** are you can get from **php_info()** in case run php on server or
call **php -i | grep php\.ini** or **php -i | grep extension_dir** from CLI.


## Initialization

Be sure that you have already registered at the [Dev Portal](https://developer.virgilsecurity.com/account/signin) and created your application.

To initialize the SDK at the __Client Side__, you need only the __Access Token__ created for a client at Dev Portal. The Access Token helps to authenticate client's requests.

```php
$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');
```


To initialize the SDK at the __Server Side__, you need the application credentials (__Access Token__, __App ID__, __App Key__ and __App Key Password__) you got during Application registration at the Dev Portal.

```php
$virgilApiContext = VirgilApiContext::create(
    [
        // use Application Access Token
        VirgilApiContext::AccessToken => '[YOUR_ACCESS_TOKEN_HERE]',
        // user Application's credentials for work with Virgil Cards
        VirgilApiContext::Credentials => new AppCredentials(        
            '[YOUR_APP_ID_HERE]',
            Buffer::fromBase64('[YOUR_APP_PRIVATE_KEY_HERE]'),
            '[YOUR_APP_PRIVATE_KEY_PASS_HERE]'
        ),
    ]
);


$virgilApi = new VirgilApi($virgilApiContext);
```

## Usage Examples

Before start practicing with the usage examples be sure that the SDK is configured. Check out our [SDK configuration guides][_configure_sdk] for more information.

#### Generate and publish user's Cards with Public Keys inside on Cards Service
Use the following lines of code to create and publish a user's Card with Public Key inside on Virgil Cards Service:

```php
// generate and save Alice's Key into a key storage on a device
$aliceKey = $virgilApi->Keys->generate();
$aliceKey->save('[KEY_NAME]', '[KEY_PASSWORD]');

// create Alice's Card using her Key
$aliceCard = $virgilApi->Cards->create('alice', 'alice_member', $aliceKey);

// export the Virgil Card to a base64-encoded string
$exportedAliceCard = $aliceCard->export();

// transmit the Card to your App server
// import the Virgil Card from a string
$aliceCard = $virgilApi->Cards->import($exportedAliceCard);

// publish a Virgil Card
$virgilApi->Cards->publish($aliceCard);
```

#### Sign then encrypt data

Virgil SDK lets you use a user's Private key and his or her Cards to sign, then encrypt any kind of data.

In the following example, we load a Private Key from a customized Key Storage and get recipient's Card from the Virgil Cards Services. Recipient's Card contains a Public Key on which we will encrypt the data and verify a signature.


```php
// load Alice's Key from a key storage
$aliceKey = $virgilApi->Keys->load('[KEY_NAME]', '[KEY_PASSWORD]');

// search for Bob's Cards on Virgil Cards Service
$bobCards = $virgilApi->Cards->find(['bob']);

// prepare a message
$message = "Hey Bob, how's it going?";

// sign by Alice's key and then encrypt message for found Bob's Cards
$cipherText = $aliceKey->signThenEncrypt($message, $bobCards)->toBase64();
```

#### Decrypt then verify data
Once the Users receive the signed and encrypted message, they can decrypt it with their own Private Key and verify signature with a Sender's Card:

```php
// load a Virgil Key from a device storage
$bobKey = $virgilApi->Keys->load('[KEY_NAME]', '[OPTIONAL_KEY_PASSWORD]');

// get a sender's Virgil Card from the Virgil Cards Service
$aliceCard = $virgilApi->Cards->get('[ALICE_CARD_ID]');

// decrypt the message
$originalMessage = $bobKey->decryptThenVerify($cipherText, $aliceCard)->toString();
```

## Docs
Virgil Security has a powerful set of APIs, and the documentation below can get you started today.

In order to use the Virgil SDK with your application, you will need to first configure your application. By default, the SDK will attempt to look for Virgil-specific settings in your application but you can change it during SDK configuration.

* [Configure the SDK][_configure_sdk] documentation
  * [Setup authentication][_setup_authentication] to make API calls to Virgil Services
  * [Setup Card Manager][_card_manager] to manage user's Public Keys
  * [Setup Card Verifier][_card_verifier] to verify signatures inside of user's Card
  * [Setup Key storage][_key_storage] to store Private Keys
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


[_virgil_crypto]: https://github.com/VirgilSecurity/virgil-crypto
[_cards_service]: https://developer.virgilsecurity.com/docs/api-reference/card-service/v4
[_use_card]: https://developer.virgilsecurity.com/docs/php/how-to/public-key-management/v4/use-card-for-crypto-operation
[_get_card]: https://developer.virgilsecurity.com/docs/php/how-to/public-key-management/v4/get-card
[_search_card]: https://developer.virgilsecurity.com/docs/php/how-to/public-key-management/v4/search-card
[_create_card]: https://developer.virgilsecurity.com/docs/php/how-to/public-key-management/v4/create-card
[_key_storage]: https://developer.virgilsecurity.com/docs/php/how-to/setup/v4/setup-key-storage
[_card_verifier]: https://developer.virgilsecurity.com/docs/php/how-to/setup/v4/setup-card-verifier
[_card_manager]: https://developer.virgilsecurity.com/docs/php/how-to/setup/v4/setup-card-manager
[_setup_authentication]: https://developer.virgilsecurity.com/docs/php/how-to/setup/v4/setup-authentication
[_services_reference_api]: https://developer.virgilsecurity.com/docs/api-reference
[_configure_sdk]: https://developer.virgilsecurity.com/docs/how-to#sdk-configuration
[_more_examples]: https://developer.virgilsecurity.com/docs/how-to#public-key-management
[_reference_api]: https://developer.virgilsecurity.com/docs/api-reference
