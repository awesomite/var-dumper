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
final class TestObject extends TestParent
{
    public static $static = 'static value';

    public $public;

    protected $protected;

    public function setProtected($value)
    {
        $this->protected = $value;
    }
}
