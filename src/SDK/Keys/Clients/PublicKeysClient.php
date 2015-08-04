<?php

namespace Virgil\SDK\Keys\Clients;

use Virgil\SDK\Common\Clients\ApiClient,
    Virgil\SDK\Keys\Models\VirgilPublicKey,
    Virgil\SDK\Keys\Models\VirgilPublicKeysCollection,
    Virgil\SDK\Keys\Models\VirgilUserDataCollection,
    Virgil\SDK\Keys\Models\VirgilUserDataType;

class PublicKeysClient extends ApiClient implements PublicKeysClientInterface {

    public function getKey($publicKeyId) {

        $response = $this->get(
            'public-key/' . $publicKeyId
        );

        return new VirgilPublicKey(
            $response->getBody()
        );
    }

    public function searchKey($userId, $userDataType) {

        if(VirgilUserDataType::isValidType($userDataType) == false) {
            throw new \Exception('Invalid data type');
        }

        $response = $this->post(
            'user-data/actions/search',
            array(
                $userDataType => $userId
            )
        );

        $collection = new VirgilPublicKeysCollection();

        $data = $response->getBody();
        foreach($data as $item) {
            $collection->add(
                $this->getKey(
                    $item->id->public_key_id
                )
            );
        }

        return $collection;
    }

    public function addKey($accountId, $publicKey, VirgilUserDataCollection $userData) {

        $response = $this->post(
            'public-key',
            array(
                'account_id' => $accountId,
                'public_key' => $publicKey,
                'user_data'  => $userData
            )
        );

        return new VirgilPublicKey(
            $response->getBody()
        );
    }
}