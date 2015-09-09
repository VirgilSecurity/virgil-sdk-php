<?php

namespace Virgil\SDK\PrivateKeys\Models;

use Virgil\SDK\Common\Models\Base\Model;

class UserData extends Model {

    public $id;
    public $class;
    public $type;
    public $value;
    public $isConfirmed;
    public $signs = array();

    public function __construct(array $data = array()) {

        if(!empty($data)) {

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