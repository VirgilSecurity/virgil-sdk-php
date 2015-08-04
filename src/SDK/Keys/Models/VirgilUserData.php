<?php

namespace Virgil\SDK\Keys\Models;

use Virgil\SDK\Common\Models\Base\Model;

class VirgilUserData extends Model {

    public $user_data_id;
    public $class;
    public $type;
    public $value;
    public $is_confirmed;
    public $signs = array();

    public function __construct(array $data = array()) {

        if(!empty($data)) {
            if(isset($data['id']['user_data_id'])) {
                $this->user_data_id = $data['id']['user_data_id'];
            }

            foreach($data as $filed => $value) {
                if(property_exists($this, $filed)) {
                    $this->{$filed} = $value;
                }
            }
        }
    }

}