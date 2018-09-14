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
final class ArrayTooDepthDumper extends AbstractDumper
{
    public function supports($var)
    {
        return \is_array($var)
            && $this->container->getDepth()->getValue() === $this->container->getConfig()->getMaxDepth();
    }

    public function dump($var)
    {
        $c = \count($var);
        echo 'array(', $c, ') {', ($c ? '...' : ''), "}";
    }
}
