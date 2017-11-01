# Exporting Card

This guide shows how to export a **Virgil Card** to the string representation.

Set up your project environment before you begin to export a Virgil Card, with the [getting started](/docs/guides/configuration/client-configuration.md) guide.

In order to export a Virgil Card, we need to:

- Initialize the **Virgil SDK**

```php
$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');
```

- Use the code below to export the Virgil Card to its string representation.

```php
// export a Virgil Card to its string representation
$exportedAliceCard = $aliceCard->export();
```

The same mechanism works for **Global Virgil Card**.
