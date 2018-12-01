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
final class Properties extends AbstractProperties
{
    private static $mapping
        = array(
            '\ArrayObject' => '\Awesomite\VarDumper\Properties\PropertiesArrayObject',
            '\Closure'     => '\Awesomite\VarDumper\Properties\PropertiesClosure',
        );

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
