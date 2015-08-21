<?php

namespace Virgil\SDK\PrivateKeys\Models;

use Virgil\SDK\Common\Models\Base\Model;

class Container extends Model {

    public $containerType;
    public $password;

    public function __construct(array $data = array()) {

        if(!empty($data)) {
            if(isset($data['container_type'])) {
                $this->containerType = $data['container_type'];
            }

            if(isset($data['password'])) {
                $this->password = $data['password'];
            }
        }
    }

}