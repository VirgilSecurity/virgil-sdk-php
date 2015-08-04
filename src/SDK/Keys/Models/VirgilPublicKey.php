<?php

namespace Virgil\SDK\Keys\Models;

use Virgil\SDK\Common\Models\Base\Model;

class VirgilPublicKey extends Model {

   public $public_key_id;
   public $public_key;
   public $user_data;

    public function __construct(array $data = array()) {
        $this->user_data  = new VirgilUserDataCollection();

        if(!empty($data)) {
            if(isset($data['id'])) {
                $this->public_key_id = $data['id']['public_key_id'];
            }

            if(isset($data['public_key'])) {
                $this->public_key = base64_decode($data['public_key']);
            }

            if(isset($data['user_data']) && is_array($data['user_data'])) {
                foreach($data['user_data'] as $item) {
                    $this->user_data->add(new VirgilUserData($item));
                }
            }
        }
    }

}
