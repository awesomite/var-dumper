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
class Hasher72 extends BaseHasher
{
    public function getHashId($object)
    {
        $this->validateObject($object);

        return (string)\spl_object_id($object);
    }
}
