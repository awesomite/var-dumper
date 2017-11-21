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

        return spl_object_id($object);
    }
}
