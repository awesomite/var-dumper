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
class HasherFactory
{
    /**
     * @return HasherInterface
     */
    public static function create()
    {
        return \function_exists('spl_object_id')
            ? new Hasher72()
            : new Hasher();
    }
}
