<?php

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
class VarProperty implements PropertyInterface
{
    const VISIBILITY_PUBLIC = 'public';
    const VISIBILITY_PROTECTED = 'protected';
    const VISIBILITY_PRIVATE = 'private';

    private $name;

    private $value;

    private $static;

    private $virtual;

    private $visibility;

    private $declaringClass;

    /**
     * @param string $name
     * @param $value
     * @param string $visibility
     * @param string $declaringClass
     * @param bool $static
     * @param bool $virtual
     */
    public function __construct($name, $value, $visibility, $declaringClass, $static = false, $virtual = false)
    {
        $visibilityOptions = array(
            static::VISIBILITY_PUBLIC,
            static::VISIBILITY_PROTECTED,
            static::VISIBILITY_PRIVATE,
        );
        if (!in_array($visibility, $visibilityOptions, true)) {
            throw new \InvalidArgumentException('Invalid value of $visibility!');
        }

        $this->name = $name;
        $this->value = $value;
        $this->visibility = $visibility;
        $this->declaringClass = $declaringClass;
        $this->static = $static;
        $this->virtual = $virtual;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isStatic()
    {
        return $this->static;
    }

    public function isVirtual()
    {
        return $this->virtual;
    }

    public function isPublic()
    {
        return $this->visibility === static::VISIBILITY_PUBLIC;
    }

    public function isProtected()
    {
        return $this->visibility === static::VISIBILITY_PROTECTED;
    }

    public function isPrivate()
    {
        return $this->visibility === static::VISIBILITY_PRIVATE;
    }

    public function getDeclaringClass()
    {
        return $this->declaringClass;
    }
}
