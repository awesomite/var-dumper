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
        $result = array();
        do {
            $result += $this->getDeclaredPropertiesForReflection($reflection);
        } while ($reflection = $reflection->getParentClass());

        return $result;
    }

    private function getDeclaredPropertiesForReflection(\ReflectionClass $reflection)
    {
        $result = array();
        foreach ($reflection->getProperties() as $property) {
            $result[$property->getDeclaringClass()->getName() . '__' . $property->getName()] = $property;
        }

        return $result;
    }
}