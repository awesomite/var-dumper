<?php

namespace Awesomite\VarDumper\Objects;

/**
 * @internal
 */
interface HasherInterface
{
    /**
     * Returns hash id for given object
     *
     * @param object $object
     * @return string
     */
    public function getHashId($object);
}
