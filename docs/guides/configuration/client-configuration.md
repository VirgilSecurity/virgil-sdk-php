# Client Configuration

In order to use the Virgil Infrastructure, set up your client and implement the required mechanisms using the following guide.


## Install SDK

The Virgil SDK is provided as a package named virgil/sdk. The package is distributed via composer package management system.

You need to install php virgil crypto extension ext-virgil_crypto_php as one of dependency otherwise you will get the requested PHP extension virgil_crypto_php is missing from your system error during composer install.

In general to install virgil crypto extension follow next steps:
- Download proper extension package for your platform from cdn like virgil-crypto-2.0.4-php-5.6-linux-x86_64.tgz.
- Type following command to unpack extension in terminal:
```php
$ tar -xvzf virgil-crypto-2.0.4-php-5.6-linux-x86_64.tgz
```
- Place unpacked virgil_crypto_php.so under php extension path.
- Add virgil extension to your php.ini configuration file like extension = virgil_crypto_php.so.
```php
$ echo "extension=virgil_crypto_php.so" >> \
  `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
```

All necessary information about where php.ini or extension_dir are you can get from php_info() in case run php on server or call php -i | grep php.ini or php -i | grep extension_dir from CLI.

Prerequisites:
- PHP 5.6.*
- Composer
- virgil_crypto_php.so

Installing the package:
```php
$ composer require virgil/sdk
```


## Obtain an Access Token
When users want to start sending and receiving messages on computer or mobile device, Virgil can't trust them right away. Clients have to be provided with a unique identity, thus, you'll need to give your users the Access Token that tells Virgil who they are and what they can do.

Each your client must send to you the Access Token request with their registration request. Then, your service that will be responsible for handling access requests must handle them in case of users successful registration on your Application server.

```
// an example of an Access Token representation
AT.7652ee415726a1f43c7206e4b4bc67ac935b53781f5b43a92540e8aae5381b14
```

## Initialize SDK

### With a Token
With the Access Token we can initialize the Virgil PFS SDK on the client side to start doing fun stuff like sending and receiving messages. To initialize the **Virgil SDK** at the client side, you need to use the following code:

```php
$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');
```

### Without a Token

In case of a **Global Virgil Card** creation you don't need to initialize the SDK with the Access Token. For more information about the Global Virgil Card creation check out the [Creating Global Card guide](/docs/guides/virgil-card/creating-global-card.md).

Use the following code to initialize the Virgil SDK without the Access Token.

```php
$virgilApi = VirgilApi::create();
```
