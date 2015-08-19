<?php

namespace Virgil\SDK\Keys\Models;

use Virgil\SDK\Common\Models\Base\Model;

class VirgilPublicKey extends Model {

   public $accountId;
   public $publicKeyId;
   public $publicKey;
   public $userData;

    public function __construct(array $data = array()) {

        if(!empty($data)) {

            if(isset($data['id'])) {
                $this->accountId   = $data['id']['account_id'];
                $this->publicKeyId = $data['id']['public_key_id'];
            }

            if(isset($data['public_key'])) {
                $this->publicKey = base64_decode(
                    $data['public_key']
                );
            }

            if(isset($data['user_data']) && is_array($data['user_data'])) {
                $this->userData  = new VirgilUserDataCollection();
                foreach($data['user_data'] as $item) {
                    $this->userData->add(
                        new VirgilUserData(
                            $item
                        )
                    );
                }
            }
        }
    }

}
