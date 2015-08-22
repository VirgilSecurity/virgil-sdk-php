<?php

namespace Virgil\SDK\Keys\Models;

use Virgil\SDK\Common\Models\Base\Collection;

class UserDataCollection extends Collection {

    public function add(UserData $object) {

        parent::add(
            $object
        );
    }
}