<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Objects;

/**
 * @internal
 */
abstract class BaseHasher implements HasherInterface
{
    protected function validateObject($object)
    {
        if (!\is_object($object)) {
            throw new \InvalidArgumentException(\sprintf('%s::getHashId expects parameter 1 to be object, %s given', \get_class($this), \gettype($object)));
        }
    }
}
