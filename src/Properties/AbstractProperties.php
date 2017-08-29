<?php

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
abstract class AbstractProperties implements PropertiesInterface
{
    protected $object;

    protected function getDeclaredProperties()
    {
        $reflection = new \ReflectionObject($this->object);

        return $reflection->getProperties();
    }
}
