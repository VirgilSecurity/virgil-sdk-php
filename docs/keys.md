# Virgil Security PHP library

- [Introduction](#introduction)
- [Build prerequisite](#build-prerequisite)
- [Build](#build)
- [Examples](#examples)
    - [General statements](#general-statements)
    - [Example 1: Generate keys](#example-1)
    - [Example 2: Register new user on the Keys service](#example-2)
    - [Example 3: Get user's public key from the Keys service](#example-3)
    - [Example 4: Search Public Key data from the Keys service](#example-4)
    - [Example 5: Search Public Key signed data from the Keys service](#example-5)
    - [Example 6: Update Public Key data](#example-6)
    - [Example 7: Delete Public Key data](#example-7)
    - [Example 8: Reset Public Key](#example-8)
    - [Example 9: Persist Public Key](#example-9)
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

  * CRUD operations for the Public Key's;
  * CRUD operations for Public Key User Data;
  * Public Key's search functionality;
  * Public Key's Reset, Persist functionality.

### General statements

1. Examples MUST be run from their directory.
2. Before run examples you have to install dependencies (run command ```composer install```)
3. All results are stored in the "data" directory.

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

### <a name="example-2"></a> Example 2: Register new user on the Keys service

> The Virgil Account will be created implicitly when the first Public Key uploaded. The application can get the information about Public Keys created only for current application. When application uploads new Public Key and there is an Account created for another application with the same UDID, the Public Key will be implicitly attached to the existing Account instance.

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

### <a name="example-3"></a> Example 3: Get user's Public Key from the Keys service

> Action purpose is to get Public Key’s data.

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

### <a name="example-4"></a> Example 4: Search Public Key data from the Keys service

> Action purpose is to search public keys by UDID values.

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

### <a name="example-5"></a> Example 5: Search Public Key signed data from the Keys service

> Action purpose is to search public keys by UDID values.

> **Note:**

> If signed version of the action is used, the public key will be returned with all user_data items for this Public Key.

> If signed version of the action is used request value parameter is ignored.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\Common\Utils\GUID,
    Virgil\SDK\Keys\Client as KeysClient;


const VIRGIL_APPLICATION_TOKEN  = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_USER_DATA_VALUE    = 'example.mail@gmail.com';
const VIRGIL_PUBLIC_KEY_ID      = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';

try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $keysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => VIRGIL_PUBLIC_KEY_ID
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

### <a name="example-6"></a> Example 6: Update Public Key data

> Action purpose is to update public key’s data.

> **Note:**

> User still controls the Public/Private Keys pair and provides request sign for authentication purposes. That’s why user authorisation is required via X-VIRGIL-REQUEST-SIGN HTTP header. Public Key modification takes place immediately after action invocation.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\Keys\Client as KeysClient;


const VIRGIL_APPLICATION_TOKEN      = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_PUBLIC_KEY_ID          = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';
const VIRGIL_PRIVATE_KEY_PASSWORD   = 'password';

try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $keysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => VIRGIL_PUBLIC_KEY_ID
    ));

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
    echo 'Call Keys service to update Public Key instance.' . PHP_EOL;
    $publicKey = $keysClient->getPublicKeysClient()->updateKey(
        VIRGIL_PUBLIC_KEY_ID,
        $publicKey,
        $privateKey,
        VIRGIL_PRIVATE_KEY_PASSWORD
    );
    echo 'Public Key instance successfully updated in Public Keys service.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-7"></a> Example 7: Delete Public Key data

> Action purpose is to remove public key’s data.

> **Note:**

> If signed version of the action is used, the public key will be removed immediately without any confirmation.

> If unsigned version of the action is used the confirmation is required. The action will return action_token response object property and will send confirmation tokens on all public key’s confirmed UDIDs. The list of masked UDID’s will be returned in user_ids response object property. To commit public key remove call persistKey() action with action_token value and the list of confirmation codes.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\Keys\Client as KeysClient;

const VIRGIL_APPLICATION_TOKEN      = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_PUBLIC_KEY_ID          = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';
const VIRGIL_PRIVATE_KEY_PASSWORD   = 'password';

try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    $keysClient->setHeaders(array(
        'X-VIRGIL-REQUEST-SIGN-PK-ID' => VIRGIL_PUBLIC_KEY_ID
    ));

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
    echo 'Call Keys service to delete Public Key instance.' . PHP_EOL;
    $result = $keysClient->getPublicKeysClient()->deleteKey(
        $publicKey,
        $privateKey,
        VIRGIL_PRIVATE_KEY_PASSWORD
    );
    echo 'Public Key instance successfully deleted from Public Keys service.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-8"></a> Example 8: Reset Public Key

> Action purpose is to reset user’s public key’s data if user lost his Private Key.

> **Note:**

> After action invocation the user will receive the confirmation tokens on all his confirmed UDIDs. The Public Key data won’t be updated until call persistKey() action is invoked with token value from this step and confirmation codes sent to UDIDs. The list of UDIDs used as confirmation tokens recipients will be listed asuser_ids response parameters.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\Keys\Client as KeysClient;


const VIRGIL_APPLICATION_TOKEN      = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_PUBLIC_KEY_ID          = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';
const VIRGIL_PRIVATE_KEY_PASSWORD   = 'password';

try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
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
    echo 'Call Keys service to reset Public Key instance.' . PHP_EOL;
    $result = $keysClient->getPublicKeysClient()->resetKey(
        VIRGIL_PUBLIC_KEY_ID,
        $publicKey,
        $privateKey,
        VIRGIL_PRIVATE_KEY_PASSWORD
    );
    echo 'Public Key instance successfully resetted.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

### <a name="example-9"></a> Example 9: Persist Public Key

> The action purpose is to confirm public key’s data.

> **Note:**

> Confirm public key’s data if X-VIRGILREQUEST-SIGN HTTP header was omitted on deleteKey() action or resetKey action was invoked.

> In this case user must collect all confirmation codes sent to all confirmed UDIDs and specify them in the request body in confirmation_codes parameter as well ac action_token parameter received on previous action.

```php
<?php
require_once '../vendor/autoload.php';

use Virgil\SDK\Keys\Client as KeysClient;


const VIRGIL_APPLICATION_TOKEN = '17da4b6d03fad06954b5dccd82439b10';
const VIRGIL_PUBLIC_KEY_ID     = '5d3a8909-5fe5-2abb-232c-3cf9c277b111';
const VIRGIL_ACTION_TOKEN      = '31b4be12-9021-76bc-246d-5ecbd7a22350';


try {

    // Create Keys Service HTTP Client
    $keysClient = new KeysClient(
        VIRGIL_APPLICATION_TOKEN
    );

    // Do service call
    echo 'Call Keys service to persist Public Key instance.' . PHP_EOL;
    $publicKey = $keysClient->getPublicKeysClient()->persistKey(
        VIRGIL_PUBLIC_KEY_ID,
        VIRGIL_ACTION_TOKEN,
        array(
            'Y4A6D9'
        )
    );
    echo 'Public Key instance successfully persisted.' . PHP_EOL;

} catch (Exception $e) {

    echo 'Error:' . $e->getMessage();
}
```

## License
BSD 3-Clause. See [LICENSE](https://github.com/VirgilSecurity/virgil/blob/master/LICENSE) for details.

## Contacts
Email: <support@virgilsecurity.com>
