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
        // Bug in HHVM - spl_object_id returns the same value for 2 different objects
        // @see https://travis-ci.org/awesomite/var-dumper/jobs/428063562
        return \function_exists('spl_object_id') && !\defined('HHVM_VERSION')
            ? new Hasher72()
            : new Hasher();
    }
}
