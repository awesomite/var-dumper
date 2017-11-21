<?php

namespace Awesomite\VarDumper\Objects;

/**
 * @internal
 */
class HasherFactory
{
    /**
     * @return HasherInterface
     */
    public static function create()
    {
        return function_exists('spl_object_id')
            ? new SplHasher()
            : new Hasher();
    }
}
