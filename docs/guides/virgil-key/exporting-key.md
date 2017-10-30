# Exporting Virgil Key

This guide shows how to export a **Virgil Key** to the string representation.

Set up your project environment before you begin to export a Virgil Key, with the [getting started](/docs/guides/configuration/client-configuration.md) guide.

In order to export the Virgil Key:

- Initialize **Virgil SDK**

```php
$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');
```

- Alice Generates a Virgil Key
- After Virgil Key generation, developers can export Alice's Virgil Key to a Base64 encoded string

```php
// export a Virgil Card to its string representation.
$exportedAliceCard = $aliceCard->export();
```

Developers also can extract Public Key from a Private Key using the Virgil CLI.
