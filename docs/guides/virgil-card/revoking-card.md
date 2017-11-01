# Revoking Card

This guide shows how to revoke a **Virgil Card** from Virgil Services.

Set up your project environment before you begin to revoke a Virgil Card, with the [getting started](/docs/guides/configuration/client-configuration.md
) guide.

In order to revoke a Virgil Card, we need to:

- Initialize the **Virgil SDK** and enter Application **credentials** (**App ID**, **App Key**, **App Key password**)

```php
$virgilApiContext = VirgilApiContext::create(
    [
        VirgilApiContext::AccessToken => '[YOUR_ACCESS_TOKEN_HERE]',
        VirgilApiContext::Credentials => new AppCredentials(
            '[YOUR_APP_ID_HERE]',
            Buffer::fromBase64('[YOUR_APP_PRIVATE_KEY_HERE]'),
            '[YOUR_APP_PRIVATE_KEY_PASS_HERE]'
        ),
    ]
);

$virgilApi = new VirgilApi($virgilApiContext);
```

- Get Alice's Virgil Card by **ID** from **Virgil Services**
- Revoke Alice's Virgil Card from Virgil Services

```php
// get a Virgil Card by ID
$aliceCard = $virgilApi->Cards->get('[ALICE_CARD_ID_HERE]');

// revoke a Virgil Card
$virgilApi->Cards->revoke($aliceCard);
```
