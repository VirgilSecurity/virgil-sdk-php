<?php
namespace Virgil\Sdk\Tests\Unit\Client\VirgilServices\Mapper;


use Virgil\Sdk\Client\VirgilServices\Mapper\AbstractJsonModelMapper;

use Virgil\Sdk\Tests\BaseTestCase;

abstract class AbstractMapperTest extends BaseTestCase
{
    /** @var AbstractJsonModelMapper $mapper */
    protected $mapper;


    public function setUp()
    {
        $this->mapper = $this->getMapper();
    }


    protected abstract function getMapper();
}
