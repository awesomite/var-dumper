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
interface HasherInterface
{
    /**
     * Returns hash id for given object
     *
     * @param  object $object
     * @return string
     */
    public function getHashId($object);
}
