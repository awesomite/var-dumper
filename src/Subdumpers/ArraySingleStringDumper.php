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

use Awesomite\VarDumper\Helpers\Strings;
use Awesomite\VarDumper\Strings\LinePart;

/**
 * @internal
 */
final class ArraySingleStringDumper extends AbstractDumper
{
    public function supports($array)
    {
        return \is_array($array)
            && 1 === \count($array)
            && \array_key_exists(0, $array)
            && \is_string($array[0])
            && false === \mb_strpos($array[0], "\n")
            && \mb_strlen(Strings::prepareSingleLine($array[0])) <= $this->container->getConfig()->getMaxLineLength();
    }

    public function dump($array)
    {
        return new LinePart('array(1) {' . $this->container->getDumper()->dumpAsPart($array[0]) . '}');
    }
}
