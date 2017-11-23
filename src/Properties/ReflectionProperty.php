<?php

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
class ReflectionProperty implements PropertyInterface
{
    private $reflection;

    private $object;

    public function __construct(\ReflectionProperty $property, $object)
    {
        $this->reflection = $property;
        if (!\is_object($object)) {
            throw new \InvalidArgumentException('Argument $object is not an object!');
        }
        $this->object = $object;
    }

    public function getValue()
    {
        $this->reflection->setAccessible(true);
        $result = $this->reflection->getValue($this->object);

        return $result;
    }

    public function getName()
    {
        return $this->reflection->getName();
    }

    public function isStatic()
    {
        return $this->reflection->isStatic();
    }

    public function isVirtual()
    {
        // new \ReflectionClass($this->object) sees also virtual properties in HHVM
        $class = new \ReflectionClass(\get_class($this->object));

        do {
            if ($class->hasProperty($this->getName())) {
                return false;
            }
        } while ($class = $class->getParentClass());

        return true;
    }

    public function isPublic()
    {
        return $this->reflection->isPublic();
    }

    public function isProtected()
    {
        return $this->reflection->isProtected();
    }

    public function isPrivate()
    {
        return $this->reflection->isPrivate();
    }

    public function getDeclaringClass()
    {
        return $this->reflection->getDeclaringClass()->getName();
    }
}
