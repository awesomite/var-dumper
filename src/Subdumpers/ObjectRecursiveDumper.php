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

/**
 * @internal
 */
class ObjectRecursiveDumper extends AbstractObjectDumper
{
    public function supports($var)
    {
        return \is_object($var) && $this->container->getReferences()->in($var);
    }

    public function dump($var)
    {
        echo 'RECURSIVE object(', $this->getClassName($var), ') #', $this->container->getHasher()->getHashId($var);
    }
}
