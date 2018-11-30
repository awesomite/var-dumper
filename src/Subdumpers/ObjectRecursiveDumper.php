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

use Awesomite\VarDumper\Strings\LinePart;

/**
 * @internal
 */
final class ObjectRecursiveDumper extends AbstractObjectDumper
{
    public function supports($var)
    {
        return \is_object($var) && $this->container->getReferences()->in($var);
    }

    public function dump($var)
    {
        return new LinePart(
            'RECURSIVE object(' . $this->getClassName($var) . ') #' . $this->container->getHasher()->getHashId($var)
        );
    }
}
