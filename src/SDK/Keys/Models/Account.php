<?php

namespace Virgil\SDK\Keys\Models;

use Virgil\SDK\Common\Models\Base\Model;

class Account extends Model {

    public $account_id;
    public $public_keys;

    public function __construct(array $data = array()) {

        $this->public_keys = new PublicKeysCollection();

        if(!empty($data)) {

            if(isset($data['id']['account_id'])) {
                $this->account_id = $data['id']['account_id'];
            }

            if(!empty($data['public_keys'])) {
                foreach($data['public_keys'] as $key) {
                    $this->public_keys->add(
                        new PublicKey(
                            $key
                        )
                    );
                }
            }
        }
    }
}