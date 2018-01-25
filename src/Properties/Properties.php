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

/**
 * @internal
 */
class Properties extends AbstractProperties
{
    const PROPERTY_DEBUG_INFO = '__debugInfo()';

    private static $mapping
        = [
            \ArrayObject::class => PropertiesArrayObject::class,
            \Closure::class     => PropertiesClosure::class,
        ];

    /**
     * @param $object
     */
    public function __construct($object)
    {
        if (!\is_object($object)) {
            throw new \InvalidArgumentException('Argument $object is not an object!');
        }
        $this->object = $object;
    }

    public function getProperties()
    {
        $object = $this->object;

        if (\method_exists($object, '__debugInfo')) {
            return [
                new VarProperty(
                    static::PROPERTY_DEBUG_INFO,
                    $object->__debugInfo(),
                    VarProperty::VISIBILITY_PUBLIC,
                    \get_class($object)
                ),
            ];
        }

        foreach (self::$mapping as $classInterface => $classReader) {
            if ($object instanceof $classInterface) {
                /** @var PropertiesInterface $reader */
                $reader = new $classReader($object);

                return $reader->getProperties();
            }
        }

        return \array_map(function ($property) use ($object) {
            return new ReflectionProperty($property, $object);
        }, $this->getDeclaredProperties());
    }
}
