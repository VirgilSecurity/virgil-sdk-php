<?php

namespace Virgil\SDK\Keys\Models;

use Virgil\SDK\Common\Models\Base\Collection;

class VirgilPublicKeysCollection extends Collection {

    public function add(VirgilPublicKey $object) {
        parent::add($object);
    }
}