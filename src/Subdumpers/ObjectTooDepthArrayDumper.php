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
class ObjectTooDepthArrayDumper extends AbstractObjectDumper
{
    public function supports($var)
    {
        return \is_object($var)
            && $this->container->getDepth()->getValue() === $this->container->getConfig()->getMaxDepth();
    }

    public function dump($object)
    {
        $class = $this->getClassName($object);
        $properties = $this->getProperties($object);

        echo 'object(', $class, ') #', $this->container->getHasher()->getHashId($object), ' (', \count($properties), ') {';
        echo \count($properties) ? '...' : '';
        echo "}";
    }
}
