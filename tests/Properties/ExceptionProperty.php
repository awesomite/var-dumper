<?php

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
class ExceptionProperty
{
    private $property = 'value';

    public function __construct()
    {
        unset($this->property);
    }

    public function __get($name)
    {
        throw new \Exception('Forbidden');
    }
}
