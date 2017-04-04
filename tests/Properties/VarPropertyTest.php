<?php

namespace Awesomite\VarDumper\Properties;

use Awesomite\VarDumper\BaseTestCase;

/**
 * @internal
 */
class VarPropertyTest extends BaseTestCase
{
    /**
     * @dataProvider providerInvalidConstructor
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid value of $visibility!
     */
    public function testInvalidConstructor()
    {
        $reflection = new \ReflectionClass('Awesomite\VarDumper\Properties\VarProperty');
        $reflection->newInstanceArgs(func_get_args());
    }

    public function providerInvalidConstructor()
    {
        return array(
            array('name', 'value', false, get_class($this)),
            array('name', 'value', new \stdClass(), get_class($this)),
        );
    }
}
