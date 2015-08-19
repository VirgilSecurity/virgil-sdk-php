<?php

namespace Virgil\SDK\Keys\Models;

use Virgil\SDK\Common\Models\Base\Collection;

class VirgilUserDataCollection extends Collection {

    public function add(VirgilUserData $object) {

        parent::add(
            $object
        );
    }
}