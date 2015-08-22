<?php

namespace Virgil\SDK\PrivateKeys\Models;

use Virgil\SDK\Common\Models\Base\Collection;

class ContainerCollection extends Collection {

    public function add(PrivateKey $object) {

        parent::add($object);
    }
}