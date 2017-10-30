# Revoking Global Card

This guide shows how to revoke a **Global Virgil Card**.

Set up your project environment before you begin to revoke a Global Virgil Card, with the [getting started](/docs/guides/configuration/client-configuration.md) guide.

In order to revoke a Global Virgil Card, we need to:

-  Initialize the Virgil SDK

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

- Load Alice's **Virgil Key** from the secure storage provided by default
- Load Alice's Virgil Card from **Virgil Services**
- Initiate the Card's identity verification process
- Confirm the Card's identity using a **confirmation code**
- Revoke the Global Virgil Card from Virgil Services

```php
// load Alice's Key from secure storage provided by default.
$aliceKey = $virgilApi->Keys->load('[KEY_NAME]', '[KEY_PASSWORD]');

// load Alice's Card from Virgil Security services.
$aliceCard = $virgilApi->Cards->get('[ALICE_CARD_ID]');

// initiate Card's identity verification process.
$attempt = $aliceCard->checkIdentity();

// confirm Card's identity using confirmation code and grub validation token.
$token = $attempt->confirm(new EmailConfirmation('[CONFIRMATION_CODE]'));

// revoke Virgil Card from Virgil Security services.
$virgilApi->Cards->revokeGlobal($aliceCard, $aliceKey, $token);
```
