<?php

namespace Virgil\SDK\PrivateKeys\Models;

use Virgil\SDK\Common\Models\Base\Model;

class VirgilPrivateKeyAccount extends Model {

    public $account_id;
    public $type;
    public $private_keys;

    public function __construct(array $data = array()) {
        $this->private_keys = new VirgilPrivateKeysCollection();

        if(!empty($data)) {
            if(isset($data['account_id'])) {
                $this->account_id = $data['account_id'];
            }

            if(isset($data['account_type'])) {
                $this->type = $data['type'];
            }

            if(isset($data['data']) && is_array($data['data'])) {
                foreach($data['private_keys'] as $key) {
                    $this->private_keys->add(new VirgilPrivateKey($key));
                }
            }
        }
    }

}