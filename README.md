# Virgil Security PHP SDK 

[Installation](#installation) | [Encryption Example](#encryption-example) | [Initialization](#initialization) | [Documentation](#documentation) | [Support](#support)

[Virgil Security](https://virgilsecurity.com) provides a set of APIs for adding security to any application. In a few simple steps you can encrypt communication, securely store data, provide passwordless login, and ensure data integrity.

For a full overview head over to our PHP [Get Started][_getstarted] guides.

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
 * Download proper extension package for your platform from [cdn](https://cdn.virgilsecurity.com/virgil-crypto/php/) like **virgil-crypto-2.0.4-php-5.6-linux-x86_64.tgz**.
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

__Next:__ [Get Started with the PHP SDK][_getstarted].

## Encryption Example

Virgil Security makes it super easy to add encryption to any application. With our SDK you create a public [__Virgil Card__][_guide_virgil_cards] for every one of your users and devices. With these in place you can easily encrypt any data in the client.

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

The receiving user then uses their stored __private key__ to decrypt the message.


```php
use Virgil\Sdk\Api\VirgilApi;

// create virgil api
$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');

// load Alice's Key from storage.
$aliceKey = $virgilApi->Keys->load('alice_key_1', 'mypassword');

// decrypt the message using the key
$originalMessage = $aliceKey->decrypt($recievedMessage)
                            ->toString()
;
```

__Next:__ To [get you properly started][_guide_encryption] you'll need to know how to create and store Virgil Cards. Our [Get Started guide][_guide_encryption] will get you there all the way.

__Also:__ [Encrypted communication][_getstarted_encryption] is just one of the few things our SDK can do. Have a look at our guides on  [Encrypted Storage][_getstarted_storage], [Data Integrity][_getstarted_data_integrity] and [Passwordless Login][_getstarted_passwordless_login] for more information.

## Initialization

To use this SDK you need to [sign up for an account](https://developer.virgilsecurity.com/account/signup) and create your first __application__. Make sure to save the __app id__, __private key__ and it's __password__. After this, create an __application token__ for your application to make authenticated requests from your clients.

To initialize the SDK on the client side you will only need the __access token__ you created.

```php
use Virgil\Sdk\Api\VirgilApi;

$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');
```

> __Note:__ this client will have limited capabilities. For example, it will be able to generate new __Cards__ but it will need a server-side client to transmit these to Virgil.

To initialize the SDK on the server side we will need the __access token__, __app id__ and the __App Key__ you created on the [Developer Dashboard](https://developer.virgilsecurity.com/account/dashboard).

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

Next: [Learn more about our the different ways of initializing the PHP SDK][_guide_initialization] in our documentation.

## Documentation

Virgil Security has a powerful set of APIs, and the documentation is there to get you started today.

* [Get Started][_getstarted_root] documentation
  * [Initialize the SDK][_initialize_root]
  * [Encrypted storage][_getstarted_storage]
  * [Encrypted communication][_getstarted_encryption]
  * [Data integrity][_getstarted_data_integrity]
  * [Passwordless login][_getstarted_passwordless_login]
* [Guides][_guides]
  * [Virgil Cards][_guide_virgil_cards]
  * [Virgil Keys][_guide_virgil_keys]

## License

This library is released under the [3-clause BSD License](LICENSE.md).

## Support

Our developer support team is here to help you. You can find us on [Twitter](https://twitter.com/virgilsecurity) and [email](support).

[support]: mailto:support@virgilsecurity.com
[_getstarted_root]: https://virgilsecurity.com/docs/sdk/php/
[_getstarted]: https://virgilsecurity.com/docs/sdk/php/
[_getstarted_encryption]: https://virgilsecurity.com/docs/use-cases/encrypted-communication
[_getstarted_storage]: https://virgilsecurity.com/docs/use-cases/secure-data-at-rest
[_getstarted_data_integrity]: https://virgilsecurity.com/docs/use-cases/data-verification
[_getstarted_passwordless_login]: https://virgilsecurity.com/docs/use-cases/passwordless-authentication
[_guides]: https://stg.virgilsecurity.com/docs/sdk/php/features
[_guide_initialization]: https://virgilsecurity.com/docs/sdk/php/getting-started#initializing
[_guide_virgil_cards]: https://virgilsecurity.com/docs/sdk/php/features#virgil-cards
[_guide_virgil_keys]: https://virgilsecurity.com/docs/sdk/php/features#virgil-keys
[_guide_encryption]: https://virgilsecurity.com/docs/sdk/php/features#encryption
[_initialize_root]: https://virgilsecurity.com/docs/sdk/php/programming-guide#initializing
