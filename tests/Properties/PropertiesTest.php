<?php

namespace Awesomite\VarDumper\Properties;

use Awesomite\VarDumper\BaseTestCase;

/**
 * @internal
 */
class PropertiesTest extends BaseTestCase
{
    /**
     * @dataProvider providerGetProperties
     *
     * @param $object
     */
    public function testGetProperties($object)
    {
        $properties = new Properties($object);
        foreach ($properties->getProperties() as $property) {
            $this->assertInstanceOf('Awesomite\VarDumper\Properties\PropertyInterface', $property);
        }
    }

    public function providerGetProperties()
    {
        $obj = new \stdClass();
        $obj->foo = 'bar';

        return array(
            array(new \stdClass()),
            array($obj),
        );
    }

    /**
     * @dataProvider providerInvalidConstructor
     * @expectedException \InvalidArgumentException
     *
     * @param $object
     */
    public function testInvalidConstructor($object)
    {
        new Properties($object);
    }

    public function providerInvalidConstructor()
    {
        return array(
            array(1),
            array(false),
            array(array()),
            array(null),
        );
    }

    /**
     * @dataProvider providerArrayObject
     *
     * @param \ArrayObject $array
     * @param array $expectedNames
     */
    public function testArrayObject(\ArrayObject $array, array $expectedNames)
    {
        $properties = new Properties($array);
        $names = array();
        foreach ($properties->getProperties() as $property) {
            /** @var PropertyInterface $property */
            $names[] = $property->getName();
        }
        $this->assertSame(count($expectedNames), count($names));
        $diff = array_diff($names, $expectedNames);
        $this->assertSame(0, count($diff), 'Diff: "' . implode('", "', $diff) . '".');
    }

    public function providerArrayObject()
    {
        $object = new ArrayObject();
        $object['test'] = 'hello';
        $object->property = 'value';
        return array(
            array($object, array('privateProperty', 'property', 'storage', 'flags', 'iteratorClass')),
        );
    }
}