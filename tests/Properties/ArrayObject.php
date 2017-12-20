<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
class ArrayObject extends \ArrayObject
{
    private $privateProperty = 'private value';

    public function getArrayCopy()
    {
        $this->throwForbidden();
    }

    public function getIteratorClass()
    {
        $this->throwForbidden();
    }

    private function throwForbidden()
    {
        throw new \Exception('Forbidden!');
    }
}
