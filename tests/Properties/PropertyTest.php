<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @param string            $name
     * @param                   $value
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
        $this->private = \mt_rand(1, 1000);
        $this->protected = \mt_rand(1, 1000);
        $randValue = \mt_rand(1, 1000);

        return [
            [new ReflectionProperty($object->getProperty('public'), $this), 'public', null],
            [
                new ReflectionProperty($object->getProperty('testVariable'), $this),
                'testVariable',
                $this->testVariable,
            ],
            [new ReflectionProperty($object->getProperty('private'), $this), 'private', $this->private],
            [new ReflectionProperty($object->getProperty('protected'), $this), 'protected', $this->protected],
            [
                new VarProperty('varProperty', $randValue, VarProperty::VISIBILITY_PRIVATE, __CLASS__),
                'varProperty',
                $randValue,
                false,
            ],
        ];
    }

    /**
     * @dataProvider providerVisibility
     *
     * @param PropertyInterface $property
     * @param bool              $static
     * @param bool              $virtual
     * @param string            $visibility
     */
    public function testVisibility(PropertyInterface $property, $static, $virtual, $visibility)
    {
        $methods = ['isPublic', 'isProtected', 'isPrivate'];

        if (!\in_array($visibility, $methods)) {
            throw new \LogicException("Invalid value of \$visibility - {$visibility}!");
        }

        $this->assertSame($static, $property->isStatic());
        $this->assertSame($virtual, $property->isVirtual());
        foreach ($methods as $method) {
            $this->assertSame($method === $visibility, \call_user_func([$property, $method]));
        }
    }

    public function providerVisibility()
    {
        $self = $this;
        $createProperty = function ($name) use ($self) {
            return new ReflectionProperty(new \ReflectionProperty(\get_class($self), $name), $self);
        };
        $createVarProperty = function ($visibility, $static, $virtual) use ($self) {
            return new VarProperty('foo', 'bar', $visibility, \get_class($self), $static, $virtual);
        };

        return [
            [$createProperty('static'), true, false, 'isPublic'],
            [$createProperty('public'), false, false, 'isPublic'],
            [$createProperty('protected'), false, false, 'isProtected'],
            [$createProperty('private'), false, false, 'isPrivate'],
            [$createVarProperty(VarProperty::VISIBILITY_PUBLIC, false, false), false, false, 'isPublic'],
            [$createVarProperty(VarProperty::VISIBILITY_PUBLIC, true, false), true, false, 'isPublic'],
            [$createVarProperty(VarProperty::VISIBILITY_PUBLIC, false, true), false, true, 'isPublic'],
            [$createVarProperty(VarProperty::VISIBILITY_PROTECTED, false, false), false, false, 'isProtected'],
            [$createVarProperty(VarProperty::VISIBILITY_PRIVATE, false, false), false, false, 'isPrivate'],
        ];
    }

    /**
     * @dataProvider providerGetDeclaringClass
     *
     * @param PropertyInterface $property
     * @param string            $declaringClass
     */
    public function testGetDeclaringClass(PropertyInterface $property, $declaringClass)
    {
        $this->assertSame($declaringClass, $property->getDeclaringClass());
    }

    public function providerGetDeclaringClass()
    {
        return [
            [new VarProperty('foo', 'bar', VarProperty::VISIBILITY_PUBLIC, \get_class($this)), \get_class($this)],
            [
                new ReflectionProperty(new \ReflectionProperty(\get_class($this), 'static'), $this),
                \get_class($this),
            ],
        ];
    }
}
