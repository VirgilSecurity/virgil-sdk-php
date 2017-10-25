# Encrypted Communication

 [Set Up Your Server](#head1) | [Set Up Your Clients](#head2) | [Register Users](#head3) | [Sign & Encrypt](#head4) | [Decrypt & Verify](#head5)

It is very easy to encrypt data for secure communications in a few simple steps. In this tutorial, we are helping two people communicate with full (end-to-end) **encryption**.

Due to limited time and resources, developers often resort to third-party solutions to transfer data, which do not have an open source API, a full cycle of data security that would ensure integrity and confidentiality, thus all of your data could be read by the third party. Virgil offers a solution without these weaknesses.

![Encrypted Communication](/documentation/img/encrypted_communication_intro.png "Encrypted Communication")

See our tutorial on [Virgil & Twilio Programmable Chat](https://github.com/VirgilSecurity/virgil-demo-twilio) for best practices.


## <a name="head1"></a> Set Up Your Server
Your server should be able to authorize your users, store Application's Virgil Key and use **Virgil SDK** for cryptographic operations or for some requests to Virgil Services. You can configure your server using the [Setup Guide](/documentation/guides/configuration/server-configuration.md).


## <a name="head2"></a> Set Up Your Clients
Setup the client-side to provide your users with an access token after their registration at your Application Server to authenticate them for further operations and transmit their **Virgil Cards** to the server. Configure the client-side using the [Setup Guide](/documentation/guides/configuration/client-configuration.md).


## <a name="head3"></a> Register Users
Now you need to register the users who will participate in encrypted communications.

In order to sign and encrypt a message each user must have his own tools, which allow him to perform cryptographic operations, and these tools must contain the necessary information to identify users. In Virgil Security, these tools are the Virgil Key and the Virgil Card.

![Virgil Card](/documentation/img/Card_introduct.png "Create Virgil Card")

When we have already set up the Virgil SDK on the server & client sides, we can finally create Virgil Cards for the users and transmit the Cards to your Server for further publication on Virgil Services.


### Generate Keys and Create Virgil Card
Use the Virgil SDK on the client side to generate a new Key Pair, and then create a user's Virgil Card using recently generated Virgil Key. All keys are generated and stored on the client side.

In this example, we will pass on the user's username and a password, which will lock in their private encryption key. Each Virgil Card is signed by a user's Virgil Key, which guarantees the Virgil Card's content integrity over its life cycle.

```php
// generate and save Alice's Key
$aliceKey = $virgilApi->Keys->generate();
$aliceKey->save('[KEY_NAME]', '[KEY_PASSWORD]');

// create Alice's Card using her Key
$aliceCard = $virgilApi->Cards->create('alice', 'alice_member', $aliceKey);
```

Warning: Virgil doesn't keep a copy of your Virgil Key. If you lose a Virgil Key, there is no way to recover it.

In order for the Sender to be able to send a message, we also need a Virgil Card associated with the Recipient. It should be noted that recently created user Virgil Cards will be visible only for application users because they are related to the Application.

Read more about Virgil Cards and their types [here](/documentation/guides/virgil-card/creating-card.md).


### Transmit the Cards to Your Server

Next, you must serialize and transmit these Cards to your server, where you will Approve & Publish Users' Cards.

```php
// export a Virgil Card to its string representation
$exportedCard = $aliceCard->export();

// transmit the Virgil Card to the server
transmitToServer($exportedCard);
```

Use the [approve & publish users guide](/documentation/guides/configuration/server-configuration.md) to publish users Virgil Cards on Virgil Services.


## <a name="head4"></a> Sign & Encrypt a Message

With the user's Cards in place, we are now ready to encrypt a message for encrypted communication. In this case, we will encrypt the message using the Recipient's Virgil Card.

As previously noted we encrypt data for secure communication, but a recipient also must be sure that no third party modified any of the message's content and that they can trust a sender, which is why we provide **Data Integrity** by adding a **Digital Signature**. Therefore we must digitally sign data first and then encrypt.

![Virgil Intro](/documentation/img/Guides_introduction.png "Sign & Encrypt")

In order to sign then encrypt messages, the Sender must load their own recently generated Virgil Key and search for the receiver's Virgil Cards at Virgil Services, where all Virgil Cards are saved.

```php
// load Alice's Key from storage
$aliceKey = $virgilApi->Keys->load('[KEY_NAME]', '[KEY_PASSWORD]');

// search for Bob's Cards
$bobCards = $virgilApi->Cards->find(['bob']);

$message = 'Hey Bob, how's it going?';

// sign by Alice's key and then encrypt message for found Bob's Cards
$cipherText = $aliceKey->signThenEncrypt($message, $bobCards)->toBase64();
```

To sign a message, you will need to load Alice's Virgil Key. See [Loading Key](/documentation/guides/virgil-key/loading-key.md) guide for more details.

Now the Receiver can verify that the message was sent by a specific Sender.

### Transmission

With the signature in place, the Sender is now ready to transmit the signed and encrypted message to the Receiver.

See our tutorial on [Virgil & Twilio Programmable Chat](https://github.com/VirgilSecurity/virgil-demo-twilio) for best practices.

## <a name="head5"></a> Decrypt a Message & Verify its Signature

Once the Recipient receives the signed and encrypted message, he can decrypt and validate the message. Thus proving that the message has not been tampered with, by verifying the signature against the Sender's Virgil Card.

In order to **decrypt** the encrypted message and then verify the signature, we need to load a private receiver's Virgil Key and search for the sender's Virgil Card at Virgil Services.

```php
// load a Virgil Key from device storage
$bobKey = $virgilApi->Keys->load('[KEY_NAME]', '[OPTIONAL_KEY_PASSWORD]');

// get a sender's Virgil Card
$aliceCard = $virgilApi->Cards->get('[ALICE_CARD_ID]');

// decrypt the message
$originalMessage = $bobKey->decryptThenVerify($cipherText, $aliceCard)->toString();
```

In many cases you will need the Sender's Virgil Cards. See [Finding Cards](/documentation/guides/virgil-card/finding-card.md) guide to find them.
