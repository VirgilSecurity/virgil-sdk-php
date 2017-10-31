# Importing Virgil Key

This guide shows how to export a Virgil Key from a Base64 encoded string representation.

Set up your project environment before you begin to import a Virgil Key, with the [getting started](/docs/guides/configuration/client-configuration.md) guide.

In order to import a Virgil Key, we need to:

- Initialize **Virgil SDK**

```php
$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');
```

- Choose a Base64 encoded string
- Import the Virgil Key from the Base64 encoded string

```php
$exportedAliceCard = Buffer::fromBase64('[BASE64_ENCODED_VIRGIL_KEY]');

// import a Virgil Card to from its string representation
$importedCard = $virgilApi->Cards->import(
    $exportedAliceCard,
    '[OPTIONAL_KEY_PASSWORD]'
);
```
