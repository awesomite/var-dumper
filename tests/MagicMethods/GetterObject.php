<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\MagicMethods;

/**
 * @internal
 */
final class GetterObject extends RemovedProperty
{
    private $c = 'c';

    public function __get($name)
    {
        return $this->$name;
    }
}
