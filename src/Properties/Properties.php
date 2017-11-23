<?php

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
class Properties extends AbstractProperties
{
    private static $mapping = array(
        '\ArrayObject' => '\Awesomite\VarDumper\Properties\PropertiesArrayObject',
        '\Closure' => '\Awesomite\VarDumper\Properties\PropertiesClosure',
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

        if ($reflection = $this->getDebugInfoReflection()) {
            return array(
                new VarProperty(
                    '__debugInfo()',
                    $object->__debugInfo(),
                    VarProperty::VISIBILITY_PUBLIC,
                    \get_class($object)
                ),
            );
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

    /**
     * @return \ReflectionMethod|null
     */
    private function getDebugInfoReflection()
    {
        if (\method_exists($this->object, '__debugInfo')) {
            $reflection = new \ReflectionMethod($this->object, '__debugInfo');
            if (!$reflection->isStatic() && $reflection->isPublic() && 0 === $reflection->getNumberOfParameters()) {
                return $reflection;
            }
        }

        return null;
    }
}
