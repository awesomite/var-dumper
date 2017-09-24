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
        if (defined('HHVM_VERSION')) {
            $this->assertTrue(true);
            return;
        }
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

    /**
     * @dataProvider providerClosure
     *
     * @param \Closure $closure
     * @param PropertyInterface[] $expectedProperties
     */
    public function testClosure(\Closure $closure, array $expectedProperties)
    {
        if (defined('HHVM_VERSION')) {
            $this->assertTrue(true);
            return;
        }

        $propertiesObj = new Properties($closure);
        $properties = $propertiesObj->getProperties();
        $this->assertSame(count($properties), count($expectedProperties));
        $methods = array(
            'getName',
            'getValue',
            'getDeclaringClass',
            'isPrivate',
            'isProtected',
            'isPublic',
            'isVirtual',
            'isStatic',
        );
        foreach ($properties as $key => $value) {
            foreach ($methods as $method) {
                $this->assertSame(
                    call_user_func(array($expectedProperties[$key], $method)),
                    call_user_func(array($value, $method))
                );
            }
        }
    }

    public function providerClosure()
    {
        $result = array(
            $this->getClosureData(),
        );

        // https://travis-ci.org/awesomite/var-dumper/jobs/240526896
        if (version_compare(PHP_VERSION, '7.1') >= 0 && !defined('HHVM_VERSION')) {
            $result[] = $this->getInternalClosureName();
        }

        return $result;
    }

    private function getClosureData()
    {
        $fnCreateProperty = function ($name, $value) {
            return new VarProperty($name, $value, VarProperty::VISIBILITY_PUBLIC, 'Closure', false, true);
        };

        $closure = function () {
        };
        $properties = array(
            $fnCreateProperty('name', __NAMESPACE__ . '\\{closure}'),
            $fnCreateProperty('filename', __FILE__),
            $fnCreateProperty('startLine', __LINE__ - 4),
            $fnCreateProperty('endLine', __LINE__ - 5),
        );
        if (version_compare(PHP_VERSION, '5.4') >= 0) {
            $properties[] = $fnCreateProperty('closureScopeClass', get_class($this));
        }

        return array(
            $closure,
            $properties,
        );
    }

    private function getInternalClosureName()
    {
        $fnCreateProperty = function ($name, $value) {
            return new VarProperty($name, $value, VarProperty::VISIBILITY_PUBLIC, 'Closure', false, true);
        };

        $closure = \Closure::fromCallable('strpos');
        $properties = array(
            $fnCreateProperty('name', 'strpos'),
        );

        return array(
            $closure,
            $properties,
        );
    }
}
