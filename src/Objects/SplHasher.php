<?php

namespace Awesomite\VarDumper\Objects;

/**
 * @internal
 */
class SplHasher extends BaseHasher
{
    public function getHashId($object)
    {
        $this->validateObject($object);

        return (string) spl_object_id($object);
    }
}
