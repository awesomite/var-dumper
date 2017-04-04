<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class TestArrayObject extends \ArrayObject
{
    private $privateProperty = 'private value';

    public function getArrayCopy()
    {
        throw new \Exception();
    }
}