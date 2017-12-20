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
class ObjectRecursiveDumper extends AbstractObjectDumper
{
    private $references;
    
    public function __construct(Stack $references)
    {
        $this->references = $references;
        parent::__construct();
    }

    public function supports(&$var)
    {
        return \is_object($var) && \in_array($var, $this->references->getAll(), true);
    }

    public function dump(&$var)
    {
        echo 'RECURSIVE object(', $this->getClassName($var), ') #', self::$hasher->getHashId($var), "\n";
    }
}
