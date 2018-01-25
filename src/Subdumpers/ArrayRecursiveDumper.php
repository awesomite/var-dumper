<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Subdumpers;

use Awesomite\VarDumper\Helpers\Stack;

/**
 * @internal
 */
class ArrayRecursiveDumper implements SubdumperInterface
{
    private $references;

    private static $canCompareArrays = null;

    public function __construct(Stack $references)
    {
        $this->references = $references;
    }

    public function supports($var)
    {
        return $this->references->in($var);
    }

    public function dump($array)
    {
        echo 'RECURSIVE array(' . \count($array) . ")\n";
    }
}
