<?php

namespace Virgil\SDK\PrivateKeys\Models;

use Virgil\SDK\Common\Models\Base\Model;

class PrivateKey extends Model {

    public $publicKeyId;
    public $privateKey;

    public function __construct(array $data = array()) {

        if(isset($data['private_key'])) {
            $this->privateKey = base64_decode(
                $data['private_key']
            );
        }

        if(isset($data['public_key_id'])) {
            $this->publicKeyId = $data['public_key_id'];
        }
    }

}