<?php

namespace Virgil\SDK\PrivateKeys\Clients;

use Virgil\SDK\Common\Clients\ApiClient,
    Virgil\SDK\PrivateKeys\Models\PrivateKey,
    Virgil\SDK\Common\Utils\Sign,
    Virgil\SDK\Common\Utils\GUID;

class PrivateKeysClient extends ApiClient implements PrivateKeysClientInterface {

    public function getPrivateKey($publicKeyId) {

        return new PrivateKey(
            $this->get(
                'private-key/public-key-id/' . $publicKeyId
            )->getBody()
        );
    }

    public function createPrivateKey($publicKeyId, $privateKey, $privateKeyPassword = null) {

        $request = array(
            'private_key' => base64_encode(
                $privateKey
            ),
            'request_sign_uuid' => GUID::generate()
        );

        Sign::createRequestSign(
            $this->getConnection(),
            $request,
            $privateKey,
            $privateKeyPassword
        );

        $this->post(
            'private-key',
            $request
        );

        return $this;
    }

    public function deletePrivateKey($privateKey, $privateKeyPassword = null) {

        $request = array(
            'request_sign_uuid' => GUID::generate()
        );

        Sign::createRequestSign(
            $this->getConnection(),
            $request,
            $privateKey,
            $privateKeyPassword
        );

        $this->delete(
            'private-key',
            $request
        );

        return $this;
    }
}