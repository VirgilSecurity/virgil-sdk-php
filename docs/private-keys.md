# Virgil Security Private Keys SDK

- [Introduction](#introduction)
- [Build prerequisite](#build-prerequisite)
- [Build](#build)
- [Installation](#installation)
- [Examples](#examples)
    - [General statements](#general-statements)
    - [Example 1: Generate keys](#example-1)
    - [Example 2: Create new Container object](#example-2)
    - [Example 3: Get Container object](#example-3)
    - [Example 4: Delete Container object](#example-4)
    - [Example 5: Update Container object](#example-5)
    - [Example 6: Reset Container password](#example-6)
    - [Example 7: Persist Container object](#example-7)
    - [Example 8: Create Private Key inside Container object](#example-8)
    - [Example 9: Get Private Key object](#example-9)
    - [Example 10: Delete Private Key object](#example-10)
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

## Installation

```
php composer.phar install
```

## Examples

This section describes common case library usage scenarios, like

  * CRUD operations for the Container object;
  * CRUD operations for Private Key object;
  * Private Key's Reset, Persist functionality.

### General statements

1. Examples MUST be run from their directory.
2. Before run examples you have to install dependencies (run command ```composer install```)
3. All results are stored in the "data" directory.
4. Please before using this examples, go to the [Virgil Security, Inc](https://virgilsecurity.com) and create your first application with Application Token. Please replace example value of `VIRGIL_APPLICATION_TOKEN` variable with your real Application token.
5. Please reaplce example value of `VIRGIL_USER_NAME` to your real email. It needs to confirm some data and invocation of some endpoints inside Private Key service.

### <a name="example-1"></a> Example 1: Generate keys

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

### <a name="example-2"></a> Example 2: Create new Container object

> The Container object will be created to store future Private Key's instances.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\PrivateKeys\Client as PrivateKeysClient;


const VIRGIL_APPLICATION_TOKEN  = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_NAME          = 'example.mail@gmail.com';
const VIRGIL_USER_PASSWORD      = 'password';
const VIRGIL_PUBLIC_KEY_ID      = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';

const VIRGIL_CONTAINER_TYPE     = 'normal';
const VIRGIL_CONTAINER_PASSWORD = 'password';

const VIRGIL_PRIVATE_KEY_PASSWORD   = 'password';

try {

    // Create Keys Service HTTP Client
    $privateKeysClient = new PrivateKeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $privateKeysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => VIRGIL_PUBLIC_KEY_ID
    ));

    echo 'Reading Private Key.' . PHP_EOL;
    $privateKey = file_get_contents(
        '../data/new_private.key'
    );
    echo 'Private Key data successfully readed.' . PHP_EOL;

    // Do service call
    echo 'Call Private Key service to create Container instance.' . PHP_EOL;
    $privateKeysClient->getContainerClient()->createContainer(
        VIRGIL_CONTAINER_TYPE,
        VIRGIL_CONTAINER_PASSWORD,
        $privateKey,
        VIRGIL_PRIVATE_KEY_PASSWORD
    );
    echo 'Container instance successfully created in Private Keys service' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-3"></a> Example 3: Get existing Container object

> Action purpose is to get Container object data.

```php
<?php

require_once '../vendor/autoload.php';

use Virgil\SDK\PrivateKeys\Client as PrivateKeysClient;


const VIRGIL_APPLICATION_TOKEN  = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_NAME          = 'example.mail@gmail.com';
const VIRGIL_USER_PASSWORD      = 'password';
const VIRGIL_PUBLIC_KEY_ID      = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';

try {

    // Create Keys Service HTTP Client
    $privateKeysClient = new PrivateKeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $privateKeysClient->setAuthCredentials(
        VIRGIL_USER_NAME,
        VIRGIL_USER_PASSWORD
    );

    // Do service call
    echo 'Call Private Key service to get Container instance.' . PHP_EOL;
    $container = $privateKeysClient->getContainerClient()->getContainer(
        VIRGIL_PUBLIC_KEY_ID
    );
    echo 'Container instance successfully fetched from Private Keys service.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-4"></a> Example 4: Delete existing Container object

> Action purpose is to delete existing Container object from the Private Key service.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\PrivateKeys\Client as PrivateKeysClient;


const VIRGIL_APPLICATION_TOKEN    = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_NAME            = 'example.mail@gmail.com';
const VIRGIL_USER_PASSWORD        = 'password';
const VIRGIL_PUBLIC_KEY_ID        = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';
const VIRGIL_PRIVATE_KEY_PASSWORD = 'password';

try {

    // Create Keys Service HTTP Client
    $privateKeysClient = new PrivateKeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $privateKeysClient->setAuthCredentials(
        VIRGIL_USER_NAME,
        VIRGIL_USER_PASSWORD
    );

    $privateKeysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => VIRGIL_PUBLIC_KEY_ID
    ));

    echo 'Reading Private Key.' . PHP_EOL;
    $privateKey = file_get_contents(
        '../data/new_private.key'
    );
    echo 'Private Key data successfully readed.' . PHP_EOL;

    // Do service call
    echo 'Call Private Key service to delete Container instance.' . PHP_EOL;
    $privateKeysClient->getContainerClient()->deleteContainer(
        $privateKey,
        VIRGIL_PRIVATE_KEY_PASSWORD
    );
    echo 'Container instance successfully deleted from Private Keys service' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-5"></a> Example 5: Update existing Container object

> Action purpose is to update existing Container object.

> **Note:**

> By invocation of this mmethod you can change Container Type or|and Container Password

```php
<?php

require_once '../vendor/autoload.php';

use Virgil\SDK\PrivateKeys\Client as PrivateKeysClient;

const VIRGIL_APPLICATION_TOKEN    = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_NAME            = 'example.mail@gmail.com';
const VIRGIL_USER_PASSWORD        = 'password';
const VIRGIL_PUBLIC_KEY_ID        = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';

const VIRGIL_PRIVATE_KEY_PASSWORD = 'password';

const VIRGIL_CONTAINER_TYPE       = 'normal';
const VIRGIL_CONTAINER_PASSWORD   = 'password';

try {

    // Create Keys Service HTTP Client
    $privateKeysClient = new PrivateKeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $privateKeysClient->setAuthCredentials(
        VIRGIL_USER_NAME,
        VIRGIL_USER_PASSWORD
    );

    $privateKeysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => VIRGIL_PUBLIC_KEY_ID
    ));

    echo 'Reading Private Key.' . PHP_EOL;
    $privateKey = file_get_contents(
        '../data/new_private.key'
    );
    echo 'Private Key data successfully readed.' . PHP_EOL;

    // Do service call
    echo 'Call Private Key service to update Container instance.' . PHP_EOL;
    $privateKeysClient->getContainerClient()->updateContainer(
        VIRGIL_CONTAINER_TYPE,
        VIRGIL_CONTAINER_PASSWORD,
        $privateKey,
        VIRGIL_PRIVATE_KEY_PASSWORD
    );
    echo 'Container instance successfully update in Private Keys service' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-6"></a> Reset Container password

> Action purpose is to reset Private Key password to the new one in case, when user forgot it.

> **Note:**

> User can reset Private Key object pasword in case of Container Type equal 'easy'. In case of Container Type equal 'normal',
Private Key object stored in its original form.

```php
<?php

require_once '../vendor/autoload.php';

use Virgil\SDK\PrivateKeys\Client as PrivateKeysClient,
    Virgil\SDK\PrivateKeys\Models\VirgilUserData;


const VIRGIL_APPLICATION_TOKEN  = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_NAME          = 'example.mail@gmail.com';
const VIRGIL_USER_PASSWORD      = 'password';

const VIRGIL_USER_DATA_CLASS    = 'user_id';
const VIRGIL_USER_DATA_TYPE     = 'email';
const VIRGIL_USER_DATA_VALUE    = 'example.mail@gmail.com';

const VIRGIL_CONTAINER_PASSWORD = 'password';

try {

    // Create Keys Service HTTP Client
    $privateKeysClient = new PrivateKeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $privateKeysClient->setAuthCredentials(
        VIRGIL_USER_NAME,
        VIRGIL_USER_PASSWORD
    );

    $userData = new VirgilUserData();
    $userData->class = VIRGIL_USER_DATA_CLASS;
    $userData->type  = VIRGIL_USER_DATA_TYPE;
    $userData->value = VIRGIL_USER_DATA_VALUE;

    // Do service call
    echo 'Call Private Key service to reset Container password.' . PHP_EOL;
    $privateKeysClient->getContainerClient()->resetPassword(
        $userData,
        VIRGIL_CONTAINER_PASSWORD
    );
    echo 'Container password successfully resetted.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-7"></a> Example 7: Persist Container object

> Action purpose is to confirm Password Reset action.

> **Note:**

> Token that was reseived while Container Reset invocation lives 60 minutes.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\PrivateKeys\Client as PrivateKeysClient;

const VIRGIL_APPLICATION_TOKEN  = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_NAME          = 'example.mail@gmail.com';
const VIRGIL_USER_PASSWORD      = 'password';

const VIRGIL_CONFIRMATION_TOKEN = 'I9Y6Y0';

try {

    // Create Keys Service HTTP Client
    $privateKeysClient = new PrivateKeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $privateKeysClient->setAuthCredentials(
        VIRGIL_USER_NAME,
        VIRGIL_USER_PASSWORD
    );

    // Do service call
    echo 'Call Private Key service to persist Container.' . PHP_EOL;
    $privateKeysClient->getContainerClient()->persistContainer(
        VIRGIL_CONFIRMATION_TOKEN
    );    echo 'Container successfully persisted.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-8"></a> Example 8: Create Private Key inside Container object

> Action purpose is to load existing Private Key into the Private Keys service and associate it with the existing Container object.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\PrivateKeys\Client as PrivateKeysClient;


const VIRGIL_APPLICATION_TOKEN    = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_NAME            = 'example.mail@gmail.com';
const VIRGIL_USER_PASSWORD        = 'password';
const VIRGIL_PUBLIC_KEY_ID        = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';
const VIRGIL_PRIVATE_KEY_PASSWORD = 'password';

try {

    // Create Keys Service HTTP Client
    $privateKeysClient = new PrivateKeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $privateKeysClient->setAuthCredentials(
        VIRGIL_USER_NAME,
        VIRGIL_USER_PASSWORD
    );

    $privateKeysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => VIRGIL_PUBLIC_KEY_ID
    ));

    echo 'Reading Private Key.' . PHP_EOL;
    $privateKey = file_get_contents(
        '../data/new_private.key'
    );
    echo 'Private Key data successfully readed.' . PHP_EOL;

    // Do service call
    echo 'Call Private Key service to create Private Key instance.' . PHP_EOL;
    $privateKeysClient->getPrivateKeysClient()->createPrivateKey(
        VIRGIL_PUBLIC_KEY_ID,
        $privateKey,
        VIRGIL_PRIVATE_KEY_PASSWORD
    );
    echo 'Private Key instance successfully created in Private Keys service.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-9"></a> Example 9: Get Private Key object

> The action purpose is to get Private Key object.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\PrivateKeys\Client as PrivateKeysClient;


const VIRGIL_APPLICATION_TOKEN  = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_NAME          = 'example.mail@gmail.com';
const VIRGIL_USER_PASSWORD      = 'password';
const VIRGIL_PUBLIC_KEY_ID      = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';

try {

    // Create Keys Service HTTP Client
    $privateKeysClient = new PrivateKeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $privateKeysClient->setAuthCredentials(
        VIRGIL_USER_NAME,
        VIRGIL_USER_PASSWORD
    );

    // Do service call
    echo 'Call Private Key service to get Private Key instance.' . PHP_EOL;
    $privateKey = $privateKeysClient->getPrivateKeysClient()->getPrivateKey(
        VIRGIL_PUBLIC_KEY_ID
    );
    echo 'Private Key instance successfully fetched from Private Keys service' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-10"></a> Example 10: Delete Private Key object

> The acction purpose is to delete Private key object. Private Key object will be discunnected from the Container Object and then deleted from the Private Key service.


```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\PrivateKeys\Client as PrivateKeysClient;


const VIRGIL_APPLICATION_TOKEN    = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_NAME            = 'example.mail@gmail.com';
const VIRGIL_USER_PASSWORD        = 'password';
const VIRGIL_PUBLIC_KEY_ID        = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';
const VIRGIL_PRIVATE_KEY_PASSWORD = 'password';

try {

    // Create Keys Service HTTP Client
    $privateKeysClient = new PrivateKeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $privateKeysClient->setAuthCredentials(
        VIRGIL_USER_NAME,
        VIRGIL_USER_PASSWORD
    );

    $privateKeysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => VIRGIL_PUBLIC_KEY_ID
    ));

    echo 'Reading Private Key.' . PHP_EOL;
    $privateKey = file_get_contents(
        '../data/new_private.key'
    );
    echo 'Private Key data successfully readed.' . PHP_EOL;

    // Do service call
    echo 'Call Private Key service to delete Private Key instance.' . PHP_EOL;
    $privateKeysClient->getPrivateKeysClient()->deletePrivateKey(
        $privateKey,
        VIRGIL_PRIVATE_KEY_PASSWORD
    );
    echo 'Private Key instance successfully deleted from Private Keys service.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

## License
BSD 3-Clause. See [LICENSE](https://github.com/VirgilSecurity/virgil/blob/master/LICENSE) for details.

## Contacts
Email: <support@virgilsecurity.com>
