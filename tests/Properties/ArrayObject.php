<?php

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
class ArrayObject extends \ArrayObject
{
    private $privateProperty = 'private value';

    public function getArrayCopy()
    {
        $this->throwForbidden();
    }

    public function getIteratorClass()
    {
        $this->throwForbidden();
    }

    private function throwForbidden()
    {
        throw new \Exception('Forbidden!');
    }
}
