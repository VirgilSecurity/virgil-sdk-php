<?php

namespace Virgil\SDK\PrivateKeys\Clients;

use Virgil\SDK\Common\Clients\ApiClient,
    Virgil\SDK\PrivateKeys\Models\Container,
    Virgil\SDK\PrivateKeys\Models\ContainerType,
    Virgil\SDK\PrivateKeys\Models\UserData,
    Virgil\SDK\Common\Utils\Sign,
    Virgil\SDK\Common\Utils\GUID;

class ContainerClient extends ApiClient implements ContainerClientInterface {

    public function getContainer($uuid) {

        $response = $this->get(
            'container/public-key-id/' . $uuid
        );

        return new Container(
            $response->getBody()
        );
    }


    public function createContainer($containerType, $containerPassword, $privateKey, $privateKeyPassword = null) {

        if(!ContainerType::isValidType($containerType)) {
            throw new \Exception('Invalid account type');
        }

        $request = array(
            'container_type' => $containerType,
            'password'       => $containerPassword
        );

        Sign::createRequestSign(
            $this->getConnection(),
            $request,
            $privateKey,
            $privateKeyPassword
        );

        $this->post(
            'container',
            $request
        );

        return $this;
    }

    public function updateContainer($containerType = null, $containerPassword = null, $privateKey, $privateKeyPassword = null) {

        $request = array();
        if(!is_null($containerType)) {
            $request['container_type'] = $containerType;
        }

        if(!is_null($containerPassword)) {
            $request['password'] = $containerPassword;
        }

        if(!empty($request)) {

            Sign::createRequestSign(
                $this->getConnection(),
                $request,
                $privateKey,
                $privateKeyPassword
            );

            $this->put(
                'container',
                $request
            );

            return $this;
        }
    }

    public function deleteContainer($privateKey, $privateKeyPassword = null) {

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
            'container',
            $request
        );

        return $this;
    }

    public function resetPassword(UserData $userData, $password) {

        $this->put(
            'container/actions/reset-password',
            array(
                'user_data' => array(
                    'class' => $userData->class,
                    'type'  => $userData->type,
                    'value' => $userData->value
                ),
                'new_password' => $password
            )
        );

        return $this;
    }

    public function persistContainer($token) {

        $this->put(
            'container/actions/persist',
            array(
                'token' => $token
            )
        );

        return $this;
    }
}