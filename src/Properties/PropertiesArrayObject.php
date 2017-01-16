<?php

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
class PropertiesArrayObject extends AbstractProperties
{
    const DESIRED_CLASS_NAME = 'ArrayObject';

    public function __construct(\ArrayObject $object)
    {
        $this->object = $object;
    }

    public function getProperties()
    {
        $object = $this->object;

        $reflectionClass = new \ReflectionClass(static::DESIRED_CLASS_NAME);
        $flags = $reflectionClass->getMethod('getFlags')->invoke($object);
        $reflectionClass->getMethod('setFlags')->invoke($object, \ArrayObject::STD_PROP_LIST);

        $properties = array_map(function (\ReflectionProperty $property) use ($object) {
            return new ReflectionProperty($property, $object);
        }, $this->getDeclaredProperties());

        if (!defined('HHVM_VERSION')) {
            $properties[] = new VarProperty(
                'storage',
                $reflectionClass->getMethod('getArrayCopy')->invoke($object),
                VarProperty::VISIBILITY_PRIVATE,
                static::DESIRED_CLASS_NAME
            );
            $properties[] = new VarProperty(
                'flags',
                $flags,
                VarProperty::VISIBILITY_PRIVATE,
                static::DESIRED_CLASS_NAME
            );
            $properties[] = new VarProperty(
                'iteratorClass',
                $reflectionClass->getMethod('getIteratorClass')->invoke($object),
                VarProperty::VISIBILITY_PRIVATE,
                static::DESIRED_CLASS_NAME
            );
        }

        $reflectionClass->getMethod('setFlags')->invoke($object, $flags);

        return $properties;
    }
}