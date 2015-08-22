<?php

namespace Virgil\SDK\Keys\Models;

use Virgil\SDK\Common\Models\Base\Collection;

class PublicKeysCollection extends Collection {

    public function add(PublicKey $object) {

        parent::add($object);
    }
}