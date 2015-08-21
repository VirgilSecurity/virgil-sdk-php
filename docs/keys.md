# Virgil Security PHP library

- [Introduction](#introduction)
- [Build prerequisite](#build-prerequisite)
- [Build](#build)
- [Examples](#examples)
    - [General statements](#general-statements)
    - [Example 1: Generate keys](#example-1)
    - [Example 2: Register user on the PKI service](#example-2)
    - [Example 3: Get user's public key from the PKI service](#example-3)
    - [Example 4: Encrypt data](#example-4)
    - [Example 5: Decrypt data](#example-5)
    - [Example 6: Sign data](#example-6)
    - [Example 7: Verify data](#example-7)
- [License](#license)
- [Contacts](#contacts)

## Introduction

This branch focuses on the PHP library implementation and covers next topics:

  * build prerequisite;
  * build;
  * usage exmaples.

Common library description can be found [here](https://github.com/VirgilSecurity/virgil).

## Build prerequisite

1. [CMake](http://www.cmake.org/).
1. [Git](http://git-scm.com/).
1. [Python](http://python.org/).
1. [Python YAML](http://pyyaml.org/).
1. C/C++ compiler:
    [gcc](https://gcc.gnu.org/),
    [clang](http://clang.llvm.org/),
    [MinGW](http://www.mingw.org/),
    [Microsoft Visual Studio](http://www.visualstudio.com/), or other.
1. [libcurl](http://curl.haxx.se/libcurl/).

## Build

1. Open terminal.
2. Clone project. ``` git clone https://github.com/VirgilSecurity/virgil.git ```
4. Navigate to the project's folder.
5. ``` cd virgil_lib ```
6. Create folder for the build purposes. ``` mkdir build ```
7. Navigate to the "build" folder. ``` cd build ```
8. Configure cmake. Note, replace "../install" path, if you want install library in different location.
 ``` cmake -DPLATFORM_NAME=PHP -DCMAKE_INSTALL_PREFIX=../install .. ```
10. Build library. ``` make ```
11. Install library. ``` make install ```
12. Add to your php.ini ```extension=path/to/your/virgil_php.so```, replace ``"path/to/your/virgil_php.so"`` to your path where virgil_php.so extension is located

## Examples

This section describes common case library usage scenarios, like

  * encrypt data for user identified by email, phone, etc;
  * sign data with own private key;
  * verify data received via email, file sharing service, etc;
  * decrypt data if verification successful.

### General statements

1. Examples MUST be run from their directory.
2. Before run examples you have to install dependencies (run command ```composer install```)
3. All results are stored in the "data" directory.

### <a name="example-1"></a> Example 1: Generate keys

*Input*:

*Output*: Public Key and Private Key

```php
<?php

require_once './vendor/autoload.php';

use Virgil\Crypto\VirgilKeyPair;

$key = new VirgilKeyPair('password');

echo 'Generate keys with with password: "password".' . PHP_EOL;
file_put_contents(
    'data' . DIRECTORY_SEPARATOR . 'new_public.key',
    $key->publicKey()
);

file_put_contents(
    'data' . DIRECTORY_SEPARATOR . 'new_private.key',
    $key->privateKey()
);
echo 'Private and Public keys were successfully generated.' . PHP_EOL;
```

### <a name="example-2"></a> Example 2: Register new user on the Keys service

```php
<?php

use Virgil\SDK\Keys\Models\VirgilUserData,
    Virgil\SDK\Keys\Models\VirgilUserDataCollection,
    Virgil\SDK\Keys\Client as KeysClient;

require_once '../vendor/autoload.php';

const VIRGIL_APPLICATION_TOKEN      = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_DATA_CLASS        = 'user_id';
const VIRGIL_USER_DATA_TYPE         = 'email';
const VIRGIL_USER_DATA_VALUE        = 'example.email@gmail.com';
const VIRGIL_PRIVATE_KEY_PASSWORD   = 'password';

try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $userData = new VirgilUserData();
    $userData->class = VIRGIL_USER_DATA_CLASS;
    $userData->type  = VIRGIL_USER_DATA_TYPE;
    $userData->value = VIRGIL_USER_DATA_VALUE;

    $userDataCollection = new VirgilUserDataCollection();
    $userDataCollection->add(
        $userData
    );

    echo 'Reading Public Key.' . PHP_EOL;
    $publicKey = file_get_contents(
        '../data/new_public.key'
    );
    echo 'Public Key data successfully readed.' . PHP_EOL;


    echo 'Reading Private Key.' . PHP_EOL;
    $privateKey = file_get_contents(
        '../data/new_private.key'
    );
    echo 'Private Key data successfully readed.' . PHP_EOL;

    // Do service call
    echo 'Call Keys service to create Public Key instance.' . PHP_EOL;
    $publicKey = $keysClient->getPublicKeysClient()->createKey(
        $publicKey,
        $userDataCollection,
        $privateKey,
        VIRGIL_PRIVATE_KEY_PASSWORD
    );
    echo 'Public Key instance successfully created in Public Keys service.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-3"></a> Example 3: Get user's public key from the Keys service

```php
<?php

require_once '../vendor/autoload.php';

use Virgil\SDK\Keys\Client as KeysClient;

const VIRGIL_APPLICATION_TOKEN = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_PUBLIC_KEY_ID     = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';

try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    // Do service call
    echo 'Call Keys service to get Public Key instance.' . PHP_EOL;
    $publicKey = $keysClient->getPublicKeysClient()->getKey(
        VIRGIL_PUBLIC_KEY_ID
    );
    echo 'Public Key instance successfully returned Public Keys instance.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-4"></a> Example 4: Grab Public Key data from the Keys service.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\Keys\Client as KeysClient;

const VIRGIL_APPLICATION_TOKEN  = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_DATA_VALUE    = 'example.mail@gmail.com';

try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    // Do service call
    echo 'Call Keys service to grab Public Key instance.' . PHP_EOL;
    $result = $keysClient->getPublicKeysClient()->grabKey(
        VIRGIL_USER_DATA_VALUE
    );
    echo 'Public Key instance successfully grabbed from Keys service.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-5"></a> Example 5: Grab Public Key signed data from the Keys service.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\Common\Utils\GUID,
    Virgil\SDK\Keys\Client as KeysClient;


const VIRGIL_APPLICATION_TOKEN  = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_DATA_VALUE    = 'example.mail@gmail.com';

try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $keysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => '5d3a8909-5fe5-2abb-232c-3cf9c277b111'
    ));

    echo 'Read Private Key.' . PHP_EOL;
    $privateKey = file_get_contents(
        '../data' . DIRECTORY_SEPARATOR . 'new_private.key'
    );
    echo 'Private Key is:' . PHP_EOL;
    echo $privateKey . PHP_EOL;
    $privateKeyPassword = 'password';

    // Do service call
    echo 'Call Keys service to grab Public Key instance.' . PHP_EOL;
    $result = $keysClient->getPublicKeysClient()->grabKey(
        VIRGIL_USER_DATA_VALUE,
        $privateKey,
        $privateKeyPassword
    );
    echo 'Public Key instance successfully grabbed from Keys service.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-6"></a> Example 6: Grab Public Key signed data from the Keys service.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\Common\Utils\GUID,
    Virgil\SDK\Keys\Client as KeysClient;


const VIRGIL_APPLICATION_TOKEN  = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_DATA_VALUE    = 'example.mail@gmail.com';

try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $keysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => '5d3a8909-5fe5-2abb-232c-3cf9c277b111'
    ));

    echo 'Read Private Key.' . PHP_EOL;
    $privateKey = file_get_contents(
        '../data' . DIRECTORY_SEPARATOR . 'new_private.key'
    );
    echo 'Private Key is:' . PHP_EOL;
    echo $privateKey . PHP_EOL;
    $privateKeyPassword = 'password';

    // Do service call
    echo 'Call Keys service to grab Public Key instance.' . PHP_EOL;
    $result = $keysClient->getPublicKeysClient()->grabKey(
        VIRGIL_USER_DATA_VALUE,
        $privateKey,
        $privateKeyPassword
    );
    echo 'Public Key instance successfully grabbed from Keys service.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-5"></a> Example 5: Decrypt data

*Input*: Encrypted data, Virgil Public Key, Private Key, Private Key password

*Output*: Decrypted data

```php
<?php

require_once './vendor/autoload.php';

try {
    echo 'Read encrypted data' . PHP_EOL;

    $source = file_get_contents('data' . DIRECTORY_SEPARATOR . 'test.txt.enc');
    if($source === false) {
        throw new Exception('Unable to get source data');
    }

    echo 'Initialize cipher' . PHP_EOL;

    $cipher     = new VirgilCipher();
    $privateKey = file_get_contents('data' . DIRECTORY_SEPARATOR . 'new_private.key');

    if($privateKey === false) {
        throw new Exception('Unable to read private key file');
    }

    $virgilCertificate = new VirgilCertificate();
    $virgilCertificate->fromJson(file_get_contents('data' . DIRECTORY_SEPARATOR . 'virgil_public.key'));

    echo 'Decrypt data' . PHP_EOL;

    $decryptedData = $cipher->decryptWithKey($source, $virgilCertificate->id()->certificateId(), $privateKey, 'password');

    echo 'Save decrypted data to file' . PHP_EOL;

    file_put_contents('data' . DIRECTORY_SEPARATOR . 'decrypted.test.txt', $decryptedData);

} catch (Exception $e) {
    echo $e->getMessage();
}
```

### <a name="example-6"></a> Example 6: Sign data

*Input*: Data, Virgil Public Key, Private Key

*Output*: Virgil Sign

```php
<?php

require_once './vendor/autoload.php';

try {
    echo 'Read source file' . PHP_EOL;

    $source = file_get_contents('data' . DIRECTORY_SEPARATOR . 'test.txt');
    if($source === false) {
        throw new Exception('Unable to get source data');
    }

    echo 'Read public key from json' . PHP_EOL;

    $publicKeyJson = file_get_contents('data' . DIRECTORY_SEPARATOR . 'virgil_public.key');
    if($publicKeyJson === false) {
        throw new Exception('Failed to open public key file');
    }

    $virgilCertificate = new VirgilCertificate();
    $virgilCertificate->fromJson($publicKeyJson);

    echo 'Read private key' . PHP_EOL;

    $privateKey = file_get_contents('data' . DIRECTORY_SEPARATOR . 'new_private.key');
    if($privateKey === false) {
        throw new Exception('Failed to open private key file');
    }

    echo 'Initialize signer' . PHP_EOL;

    $signer = new VirgilSigner();

    echo 'Sign data' . PHP_EOL;

    $sign = $signer->sign($source, $virgilCertificate->id()->certificateId(), $privateKey, 'password');

    echo 'Save signed data to file' . PHP_EOL;

    file_put_contents('data' . DIRECTORY_SEPARATOR . 'test.txt.sign', $sign->toJson());

} catch (Exception $e) {
    echo $e->getMessage();
}
```

### <a name="example-7"></a> Example 7: Verify data

*Input*: Data, Sign, Virgil Public Key

*Output*: Verification result

```php
<?php

require_once './vendor/autoload.php';

try {
    echo 'Read source file' . PHP_EOL;

    $source = file_get_contents('data' . DIRECTORY_SEPARATOR . 'test.txt');
    if($source === false) {
        throw new Exception('Unable to get source data');
    }

    echo 'Read sign from json' . PHP_EOL;

    $signJson = file_get_contents('data' . DIRECTORY_SEPARATOR . 'test.txt.sign');
    if($signJson === false) {
        throw new Exception('Filed to open sign file');
    }

    $sign = new VirgilSign();
    $sign->fromJson($signJson);

    echo 'Read public key from json' . PHP_EOL;

    $publicKeyJson = file_get_contents('data' . DIRECTORY_SEPARATOR . 'virgil_public.key');
    if($publicKeyJson === false) {
        throw new Exception('Failed to open public key file');
    }

    $virgilCertificate = new VirgilCertificate();
    $virgilCertificate->fromJson($publicKeyJson);

    echo 'Initialize signer' . PHP_EOL;

    $signer = new VirgilSigner();

    echo 'Verify sign' . PHP_EOL;

    if($signer->verify($source, $sign, $virgilCertificate->publicKey()) == true) {
        echo 'Data is verified';
    } else {
        echo 'Data is not verified';
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
```

## License
BSD 3-Clause. See [LICENSE](https://github.com/VirgilSecurity/virgil/blob/master/LICENSE) for details.

## Contacts
Email: <support@virgilsecurity.com>
