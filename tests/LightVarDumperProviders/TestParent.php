<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class TestParent
{
    protected static $protectedStatic = 'protected static value';

    private $private;

    public function setPrivate($value)
    {
        $this->private = $value;
    }
}
