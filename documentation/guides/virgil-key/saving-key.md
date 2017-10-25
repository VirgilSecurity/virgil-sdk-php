# Saving Key

This guide shows how to save a **Virgil Key** from the default storage after its [generation](/documentation/guides/virgil-key/generating-key.md).

Set up your project environment before you begin to generate a Virgil Key, with the [getting started](/documentation/guides/configuration/client-configuration.md) guide.

In order to save the Virgil Key we need to:

- Initialize the **Virgil SDK**:

```php
$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');
```

- Save Alice's Virgil Key in the protected storage on the device

```php
// save Virgil Key into storage
$aliceKey->save('[KEY_NAME]', '[KEY_PASSWORD]');
```

Developers can also change the Virgil Key storage directory as needed, during Virgil SDK initialization.
