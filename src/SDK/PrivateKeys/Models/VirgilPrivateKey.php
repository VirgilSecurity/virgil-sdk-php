<?php

namespace Virgil\SDK\PrivateKeys\Models;

use Virgil\SDK\Common\Models\Base\Model;

class VirgilPrivateKey extends Model {

    public $public_key_id;
    public $account_id;
    public $private_key;

    public function __construct(array $data = array()) {

        if(!empty($data)) {
            foreach($data as $field => $value) {
                if(property_exists($this, $field)) {
                    $this->{$field} = $value;
                }
            }
        }
    }

}