<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Helpers;

/**
 * @internal
 */
final class IntValue
{
    private $value;

    public function __construct($value = 0)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function incr()
    {
        ++$this->value;
    }

    public function decr()
    {
        --$this->value;
    }
}
