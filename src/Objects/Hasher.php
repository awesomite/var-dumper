<?php

namespace Awesomite\VarDumper\Objects;

/**
 * @internal
 */
class Hasher implements HasherInterface
{
    private static $hashes = array();

    private static $counter = 0;

    public function getHashId($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException(sprintf(
                '%s expects parameter 1 to be object, %s given',
                __METHOD__,
                gettype($object)
            ));
        }

        $splHash = spl_object_hash($object);

        if (!isset(self::$hashes[$splHash])) {
            self::$hashes[$splHash] = ++self::$counter;
        }

        return self::$hashes[$splHash];
    }
}
