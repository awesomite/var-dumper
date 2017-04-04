<?php

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
class Properties extends AbstractProperties
{
    static private $mapping = array(
        '\ArrayObject' => '\Awesomite\VarDumper\Properties\PropertiesArrayObject',
    );

    /**
     * Properties constructor.
     * @param $object
     */
    public function __construct($object)
    {
        if (!is_object($object)) {
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

        return array_map(function ($property) use ($object) {
            return new ReflectionProperty($property, $object);
        }, $this->getDeclaredProperties());
    }
}
