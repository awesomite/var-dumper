<?php

namespace Awesomite\VarDumper\Objects;

/**
 * @internal
 */
abstract class BaseHasher implements HasherInterface
{
    protected function validateObject($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException(sprintf(
                '%s::getHashId expects parameter 1 to be object, %s given',
                get_class($this),
                gettype($object)
            ));
        }
    }
}
