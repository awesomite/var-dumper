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
class Stack
{
    private $array = array();
    
    public function pop()
    {
        return \array_pop($this->array);
    }

    public function push(&$item)
    {
        return \array_push($this->array, $item);
    }
    
    public function getAll()
    {
        return $this->array;
    }
}
