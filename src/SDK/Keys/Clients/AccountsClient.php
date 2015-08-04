<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Common\Clients\ApiClient;
use Virgil\SDK\Keys\Models\VirgilAccount;
use Virgil\SDK\Common\Utils\GUID;

class AccountsClient extends ApiClient implements AccountsClientInterface {

    public function register($userDataType, $userId, $publicKey) {
        $response = $this->post('account', array(
            'public_key'      => base64_encode($publicKey),
            'user_data_type'  => $userDataType,
            'user_data_value' => $userId,
            'guid'            => GUID::generate()
        ));

        return new VirgilAccount($response->getBody());
    }
}