<?php

namespace Awesomite\VarDumper\Properties;

use Awesomite\VarDumper\BaseTestCase;

/**
 * @internal
 */
class PropertyTest extends BaseTestCase
{
    public static $static;

    public $public;
    protected $protected;
    private $private;

    /**
     * @dataProvider providerGetValue
     *
     * @param PropertyInterface $property
     * @param string $name
     * @param $value
     */
    public function testGetValue(PropertyInterface $property, $name, $value)
    {
        $this->assertSame($name, $property->getName());
        $this->assertSame($value, $property->getValue());
    }

    public function providerGetValue()
    {
        $object = new \ReflectionObject($this);
        $this->testVariable = 'test value';
        $this->private = mt_rand(1, 1000);
        $this->protected = mt_rand(1, 1000);
        $randValue = mt_rand(1, 1000);

        return array(
            array(new ReflectionProperty($object->getProperty('public'), $this), 'public', null),
            array(
                new ReflectionProperty($object->getProperty('testVariable'), $this),
                'testVariable',
                $this->testVariable,
            ),
            array(new ReflectionProperty($object->getProperty('private'), $this), 'private', $this->private),
            array(new ReflectionProperty($object->getProperty('protected'), $this), 'protected', $this->protected),
            array(
                new VarProperty('varProperty', $randValue, VarProperty::VISIBILITY_PRIVATE, __CLASS__),
                'varProperty',
                $randValue,
                false
            ),
        );
    }

    /**
     * @dataProvider providerVisibility
     *
     * @param PropertyInterface $property
     * @param bool $static
     * @param bool $virtual
     * @param string $visibility
     */
    public function testVisibility(PropertyInterface $property, $static, $virtual, $visibility)
    {
        $methods = array('isPublic', 'isProtected', 'isPrivate');

        if (!in_array($visibility, $methods)) {
            throw new \LogicException("Invalid value of \$visibility - {$visibility}!");
        }

        $this->assertSame($static, $property->isStatic());
        $this->assertSame($virtual, $property->isVirtual());
        foreach ($methods as $method) {
            $this->assertSame($method === $visibility, call_user_func(array($property, $method)));
        }
    }

    public function providerVisibility()
    {
        $self = $this;
        $createProperty = function ($name) use ($self) {
            return new ReflectionProperty(new \ReflectionProperty(get_class($self), $name), $self);
        };
        $createVarProperty = function ($visibility, $static, $virtual) use ($self) {
            return new VarProperty('foo', 'bar', $visibility, get_class($self), $static, $virtual);
        };

        return array(
            array($createProperty('static'), true, false, 'isPublic'),
            array($createProperty('public'), false, false, 'isPublic'),
            array($createProperty('protected'), false, false, 'isProtected'),
            array($createProperty('private'), false, false, 'isPrivate'),
            array($createVarProperty(VarProperty::VISIBILITY_PUBLIC, false, false), false, false, 'isPublic'),
            array($createVarProperty(VarProperty::VISIBILITY_PUBLIC, true, false), true, false, 'isPublic'),
            array($createVarProperty(VarProperty::VISIBILITY_PUBLIC, false, true), false, true, 'isPublic'),
            array($createVarProperty(VarProperty::VISIBILITY_PROTECTED, false, false), false, false, 'isProtected'),
            array($createVarProperty(VarProperty::VISIBILITY_PRIVATE, false, false), false, false, 'isPrivate'),
        );
    }

    /**
     * @dataProvider providerGetDeclaringClass
     *
     * @param PropertyInterface $property
     * @param string $declaringClass
     */
    public function testGetDeclaringClass(PropertyInterface $property, $declaringClass)
    {
        $this->assertSame($declaringClass, $property->getDeclaringClass());
    }

    public function providerGetDeclaringClass()
    {
        return array(
            array(new VarProperty('foo', 'bar', VarProperty::VISIBILITY_PUBLIC, get_class($this)), get_class($this)),
            array(
                new ReflectionProperty(new \ReflectionProperty(get_class($this), 'static'), $this),
                get_class($this)
            ),
        );
    }
}