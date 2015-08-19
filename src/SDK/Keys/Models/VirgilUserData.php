<?php

namespace Virgil\SDK\Keys\Models;

use Virgil\SDK\Common\Models\Base\Model;

class VirgilUserData extends Model {

    public $id;
    public $class;
    public $type;
    public $value;
    public $isConfirmed;
    public $signs = array();

    public function __construct(array $data = array()) {

        if(!empty($data)) {

            if(isset($data['id']['account_id'])) {
                $this->id->account_id = $data['id']['account_id'];
            }

            if(isset($data['id']['public_key_id'])) {
                $this->id->public_key_id = $data['id']['public_key_id'];
            }

            if(isset($data['id']['user_data_id'])) {
                $this->id->user_data_id = $data['id']['user_data_id'];
            }

            foreach($data as $field => $value) {
                $field = $this->toCamelcase(
                    $field
                );
                if(property_exists($this, $field)) {
                    $this->{$field} = $value;
                }
            }
        }
    }

}