# Virgil Security PHP SDK

[Installation](#installation) | [Initialization](#initialization) | [Encryption / Decryption Example](#encryption-example) |  [Documentation](#documentation) | [Support](#support)

[[Virgil Security](https://virgilsecurity.com) provides a set of APIs for adding security to any application. In a few steps, you can encrypt communication, securely store data, provide passwordless authentication, and ensure data integrity.

To initialize and use Virgil SDK, you need to have [Developer Account](https://developer.virgilsecurity.com/account/signin).

## Installation

The Virgil SDK is provided as a package named *virgil/sdk*. The package is distributed via **composer** package management system.

To install package use the command below:

1. Go to the your project root directory.
2. Run

```
$ composer require virgil/sdk
```

You need to install php virgil crypto extension *ext-virgil_crypto_php* as one of dependency otherwise you will get `the requested PHP extension virgil_crypto_php is missing from your system` error during composer install.

In general to install virgil crypto extension follow next steps:
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

To initialize the SDK at the __Client Side__ you need only the __Access Token__ created for a client at [Dev Portal](https://developer.virgilsecurity.com/account/signin). The Access Token helps to authenticate client's requests.

```php
use Virgil\Sdk\Api\VirgilApi;

$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');
```


To initialize the SDK at the __Server Side__ you need the application credentials (__Access Token__, __App ID__, __App Key__ and __App Key Password__) you got during Application registration at the [Dev Portal](https://developer.virgilsecurity.com/account/signin).

```php
use Virgil\Sdk\Buffer;

use Virgil\Sdk\Api\AppCredentials;
use Virgil\Sdk\Api\VirgilApiContext;
use Virgil\Sdk\Api\VirgilApi;

$virgilApiContext = VirgilApiContext::create(
    [
        VirgilApiContext::AccessToken => '[YOUR_ACCESS_TOKEN_HERE]', //sets application access token
        VirgilApiContext::Credentials => new AppCredentials(        //sets a credentials to work with application virgil cards
            '[YOUR_APP_ID_HERE]', Buffer::fromBase64('[YOUR_APP_PRIVATE_KEY_HERE]'), '[YOUR_APP_PRIVATE_KEY_PASS_HERE]'
        ),
    ]
);


$virgilApi = new VirgilApi($virgilApiContext);
```


## Encryption / Decryption Example

Virgil Security simplifies adding encryption to any application. With our SDK you may create unique Virgil Cards for your all users and devices. With users' Virgil Cards, you can easily encrypt any data at Client Side.

```php
use Virgil\Sdk\Api\VirgilApi;

// create virgil api
$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');

// find Alice's card(s)
$aliceCards = $virgilApi->Cards->find(['alice']);

$message = 'Hello Alice!';

// encrypt the message using Alice's cards
$encryptedMessage = $aliceCards->encrypt($message);

// transmit the message with your preferred technology
$this->transmitMessage($encryptedMessage->toBase64());
```

Alice uses her Virgil Private Key to decrypt the encrypted message.


```php
use Virgil\Sdk\Api\VirgilApi;

// create virgil api
$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');

// load Alice's Key from local storage.
$aliceKey = $virgilApi->Keys->load('alice_key_1', 'mypassword');

// decrypt the message using the Alice Virgil key
$originalMessage = $aliceKey->decrypt($recievedMessage)
                            ->toString()
;
```

__Next:__ On the page below you can find configuration documentation and the list of our guides and use cases where you can see appliance of Virgil PHP SDK.


## Documentation

Virgil Security has a powerful set of APIs and the documentation to help you get started:

* [Get Started](/documentation/get-started) documentation
  * [Encrypted storage](/documentation/get-started/encrypted-storage.md)
  * [Encrypted communication](/documentation/get-started/encrypted-communication.md)
  * [Data integrity](/documentation/get-started/data-integrity.md)
* [Guides](/documentation/guides)
  * [Virgil Cards](/documentation/guides/virgil-card)
  * [Virgil Keys](/documentation/guides/virgil-key)
  * [Encryption](/documentation/guides/encryption)
  * [Signature](/documentation/guides/signature)
* [Configuration](/documentation/guides/configuration)
  * [Set Up Client Side](/documentation/guides/configuration/client.md)
  * [Set Up Server Side](/documentation/guides/configuration/server.md)

## License

This library is released under the [3-clause BSD License](LICENSE.md).

## Support

Our developer support team is here to help you. You can find us on [Twitter](https://twitter.com/virgilsecurity) and [email][support].

[support]: mailto:support@virgilsecurity.com
