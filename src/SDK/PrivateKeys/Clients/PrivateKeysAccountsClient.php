<?php

namespace Virgil\SDK\PrivateKeys\Clients;

use Virgil\SDK\Common\Clients\ApiClient,
    Virgil\SDK\PrivateKeys\Http\ConnectionInterface,
    Virgil\SDK\PrivateKeys\Models\VirgilPrivateKey,
    Virgil\SDK\PrivateKeys\Models\VirgilPrivateKeyAccount,
    Virgil\SDK\PrivateKeys\Models\VirgilPrivateKeysAccountType,
    Virgil\SDK\PrivateKeys\Models\VirgilPrivateKeysCollection;

class PrivateKeysAccountsClient extends ApiClient implements PrivateKeysAccountsClientInterface {

    public function __construct(ConnectionInterface $connection) {

        parent::__construct(
            $connection
        );
    }

    /**
     * @return \Virgil\SDK\PrivateKeys\Http\ConnectionInterface
     */
    public function getConnection() {

        return parent::getConnection();
    }

    public function getAccount($accountId) {

        $response = $this->get(
            'private-key/account/' . $accountId
        );

        return new VirgilPrivateKeyAccount(
            $response->getBody()
        );
    }


    public function create($accountId, $accountType, $publicKeyId, $sign, $password) {
        if(VirgilPrivateKeysAccountType::isValidType($accountType)) {
            throw new \Exception('Invalid account type');
        }

        $this->post(
            'account',
            array(
                'account_id'    => $accountId,
                'account_type'  => $accountType,
                'public_key_id' => $publicKeyId,
                'sign'          => base64_encode($sign),
                'password'      => $password
            )
        );
    }

    public function remove($accountId, $publicKeyId, $sign) {

        $this->delete(
            'account',
            array(
                'account_id'    => $accountId,
                'public_key_id' => $publicKeyId,
                'sign'          => base64_encode($sign)
            )
        );
    }

    public function resetPassword($userId, $newPassword) {

        $this->put(
            'account/reset',
            array(
                'new_password' => $newPassword,
                'user_data'    => array(
                    'class' => 'user_id',
                    'type'  => 'email',
                    'value' => $userId
                )
            )
        );
    }

    public function verifyResetPassword($token) {

        $response = $this->put(
            'account/verify',
            array(
                'token' => $token
            )
        );

        $privateKeysCollection = new VirgilPrivateKeysCollection();

        $data = $response->getBody();
        if(!empty($data['data'])) {
            foreach($data['data'] as $item) {
                $item['account_id'] = $data['account_id'];

                $privateKeysCollection->add(
                    new VirgilPrivateKey($item)
                );
            }
        }

        return $privateKeysCollection;
    }
}