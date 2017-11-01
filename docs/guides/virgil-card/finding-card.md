# Finding Card

This guide shows how to find a **Virgil Card**. As previously noted, all Virgil Cards are saved at **Virgil Services** after their publication. Thus, every user can find their own Virgil Card or another user's Virgil Card on Virgil Services. It should be noted that users' Virgil Cards will only be visible to application users. Global Virgil Cards will be visible to anybody.

Set up your project environment before you begin to find a Virgil Card, with the [getting started](/docs/guides/configuration/client-configuration.md) guide.


In order to search for an **Application** or **Global Virgil Card** you need to initialize the **Virgil SDK**:

```php
$virgilApi = VirgilApi::create('[YOUR_ACCESS_TOKEN_HERE]');
```

### Application Cards

There are two ways to find an Application Virgil Card on Virgil Services:

The first one allows developers to get the Virgil Card by its unique **ID**

```php
$aliceCard = $virgilApi->Cards->get('[ALICE_CARD_ID_HERE]');
```

The second one allows developers to find Virgil Cards by *identity* and *identityType*

```php
// search for all Alice's Virgil Cards.
$aliceCards = $virgilApi->Cards->find(['alice']);

// search for all Bob's Virgil Cards with identity type 'member'
$bobCards = $virgilApi->Cards->find(['bob'], 'member');
```


### Global Cards

```php
// search for all Global Virgil Cards
$bobGlobalCards = $virgilApi->Cards->findGlobal(
    ['bob@virgilsecurity.com'],
    IdentityTypes::TYPE_EMAIL)
;

// search for Application Virgil Card
$appGlobalCards = $virgilApi->Cards->findGlobal(
    ['com.username.appname'],
    IdentityTypes::TYPE_APPLICATION
);
```
