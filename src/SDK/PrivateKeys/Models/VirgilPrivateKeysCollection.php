<?php

namespace Virgil\SDK\PrivateKeys\Models;

use Virgil\SDK\Common\Models\Base\Collection;

class VirgilPrivateKeysCollection extends Collection {

    public function add(VirgilPrivateKey $object) {

        parent::add($object);
    }
}