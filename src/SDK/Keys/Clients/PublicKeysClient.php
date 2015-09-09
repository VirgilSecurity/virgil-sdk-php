<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Common\Clients\ApiClient,
    Virgil\SDK\Keys\Models\PublicKey,
    Virgil\SDK\Keys\Models\PublicKeysCollection,
    Virgil\SDK\Keys\Models\UserDataCollection,
    Virgil\SDK\Common\Utils\GUID,
    Virgil\SDK\Common\Utils\Sign;


class PublicKeysClient extends ApiClient implements PublicKeysClientInterface {

    public function getKey($publicKeyId) {

        $response = $this->get(
            'public-key/' . $publicKeyId
        );

        return new PublicKey(
            $response->getBody()
        );
    }

    public function grabKey($userId, $privateKey = null, $privateKeyPassword = null) {

        $request = array(
            'value' => $userId,
            'request_sign_uuid' => GUID::generate()
        );

        if(!is_null($privateKey)) {
            Sign::createRequestSign(
                $this->getConnection(),
                $request,
                $privateKey,
                $privateKeyPassword
            );
        }

        $response = $this->post(
            'public-key/actions/grab',
            $request
        );

        $collection = new PublicKeysCollection();
        $collection->add(
            new PublicKey(
                $response->getBody()
            )
        );


        return $collection;
    }

    public function createKey($publicKey, UserDataCollection $userData, $privateKey, $privateKeyPassword = null) {

        $request = array(
            'public_key' => base64_encode(
                $publicKey
            ),
            'user_data' => $userData,
            'request_sign_uuid' => GUID::generate(),
        );

        Sign::createRequestSign(
            $this->getConnection(),
            $request,
            $privateKey,
            $privateKeyPassword
        );

        return new PublicKey(
            $this->post(
                'public-key',
                $request
            )->getBody()
        );
    }

    public function updateKey($publicKeyId, $oldPrivateKey, $newPublicKey, $newPrivateKey, $oldPrivateKeyPassword = null, $newPrivateKeyPassword = null) {

        $requestSignUUID = GUID::generate();
        $request = array(
            'request_sign_uuid' => $requestSignUUID,
            'public_key' => base64_encode(
                $newPublicKey
            ),
            'uuid_sign' =>
                base64_encode(
                    Sign::createSign(
                        $requestSignUUID,
                        $newPrivateKey,
                        $newPrivateKeyPassword
                    )
                )
        );

        Sign::createRequestSign(
            $this->getConnection(),
            $request,
            $oldPrivateKey,
            $oldPrivateKeyPassword
        );

        return new PublicKey(
            $this->put(
                'public-key/' . $publicKeyId,
                $request
            )->getBody()
        );

    }

    public function deleteKey($publicKeyId, $privateKey, $privateKeyPassword = null) {

        $request = array(
            'request_sign_uuid' => GUID::generate()
        );

        Sign::createRequestSign(
            $this->getConnection(),
            $request,
            $privateKey,
            $privateKeyPassword
        );

        return $this->delete(
            'public-key/' . $publicKeyId,
            $request
        )->getBody();
    }

    public function resetKey($publicKeyId, $publicKey, $privateKey, $privateKeyPassword = null) {

        $request = array(
            'public_key' => base64_encode(
                $publicKey
            ),
            'request_sign_uuid' => GUID::generate()
        );

        Sign::createRequestSign(
            $this->getConnection(),
            $request,
            $privateKey,
            $privateKeyPassword
        );

        return $this->post(
            'public-key/' . $publicKeyId . '/actions/reset',
            $request
        )->getBody();
    }

    public function persistKey($publicKeyId, $actionToken, $confirmationCodes) {

        return new PublicKey(
            $this->post(
                'public-key/' . $publicKeyId . '/persist',
                array(
                    'action_token' => $actionToken,
                    'confirmation_codes' => $confirmationCodes
                )
            )->getBody()
        );
    }
}