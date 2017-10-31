# Validating Card

This guide shows how to validate a **Virgil Card** on a device. As previously noted, each Virgil Card contains a Digital signature that provides data integrity for the Virgil Card over its life cycle. Therefore, developers can verify the Digital Signature at any time.

During the validation process we verify, by default, two signatures:
- **from Virgil Card owner**
- **from Virgil Services**

Additionally, developers can verify the **signature of the application server**.

Set up your project environment before you begin to validate a Virgil Card, with the [getting started](/docs/guides/configuration/client-configuration.md) guide.

In order to validate the signature of the Virgil Card owner, **Virgil Services**, and the Application Server, we need to:

```php
// initialize context with custom card verifiers.
$virgilApiContext = VirgilApiContext::create(
    [
        VirgilApiContext::AccessToken   => '[YOUR_ACCESS_TOKEN_HERE]',
        VirgilApiContext::CardVerifiers => [
            new CardVerifier(
                '[YOUR_CARD_ID_HERE]',
                Buffer::fromBase64('[YOUR_PUBLIC_KEY_HERE]')
            ),
        ],
    ]
);

// initialize High level Api
$virgilApi = new VirgilApi($virgilApiContext);

$aliceCards = $virgilApi->Cards->find(['alice']);
```
