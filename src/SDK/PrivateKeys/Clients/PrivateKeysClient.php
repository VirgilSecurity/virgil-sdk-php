<?php

namespace Virgil\SDK\PrivateKeys\Clients;

use Virgil\SDK\Common\Clients\ApiClient;
use Virgil\SDK\PrivateKeys\Http\ConnectionInterface;
use Virgil\SDK\PrivateKeys\Models\VirgilPrivateKey;
use Virgil\SDK\PrivateKeys\Models\VirgilPrivateKeysCollection;

class PrivateKeysClient extends ApiClient implements PrivateKeysClientInterface {

    public function __construct(ConnectionInterface $connection) {
        parent::__construct($connection);
    }

    /**
     * @return \Virgil\SDK\PrivateKeys\Http\ConnectionInterface
     */
    public function getConnection() {
        return parent::getConnection();
    }

    public function getPrivateKey($publicKeyId) {
        $response = $this->get('private-key/public-key/' . $publicKeyId);

        return new VirgilPrivateKey($response->getBody());
    }

    public function getAll($accountId) {
        $response = $this->get('private-key/account/' . $accountId);

        $collection = new VirgilPrivateKeysCollection();

        $data = $response->getBody();
        if(!empty($data['data'])) {
            foreach($data['data'] as $item) {
                $item['account_id'] = $data['account_id'];

                $collection->add(new VirgilPrivateKey($item));
            }
        }

        return $collection;
    }

    public function add($accountId, $publicKeyId, $sign, $privateKey) {
        $response = $this->post('private-key', array(
            'account_id'    => $accountId,
            'public_key_id' => $publicKeyId,
            'sign'          => base64_encode($sign),
            'private_key'   => base64_encode($privateKey)
        ));

        return new VirgilPrivateKey($response->getBody());
    }

    public function remove($publicKeyId, $sign) {
        $this->delete('private-key', array(
            'public_key_id' => $publicKeyId,
            'sign'          => $sign
        ));
    }
}