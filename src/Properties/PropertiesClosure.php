<?php

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
class PropertiesClosure implements PropertiesInterface
{
    const DESIRED_CLASS = 'Closure';

    private $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    public function getProperties()
    {
        $reflection = new \ReflectionFunction($this->closure);

        $result = array(
            $this->createProperty('name', $reflection->getName()),
        );

        if (!$reflection->isInternal()) {
            $result = array_merge($result, $this->getNonInternalProperties($reflection));
        }

        return $result;
    }

    private function getNonInternalProperties(\ReflectionFunction $reflection)
    {
        $result = array(
            $this->createProperty('filename', $reflection->getFileName()),
            $this->createProperty('startLine', $reflection->getStartLine()),
            $this->createProperty('endLine', $reflection->getEndLine()),
        );

        if (version_compare(PHP_VERSION, '5.4') >= 0) {
            if ($scopeClass = $reflection->getClosureScopeClass()) {
                $result[] = $this->createProperty('closureScopeClass', $scopeClass->getName());
            }
        }

        return $result;
    }

    private function createProperty($name, $value)
    {
        return new VarProperty(
            $name,
            $value,
            VarProperty::VISIBILITY_PUBLIC,
            static::DESIRED_CLASS,
            false,
            true
        );
    }
}
