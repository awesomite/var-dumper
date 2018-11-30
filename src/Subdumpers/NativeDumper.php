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

use Awesomite\VarDumper\InternalVarDumper;
use Awesomite\VarDumper\Strings\LinePart;

/**
 * @internal
 */
final class NativeDumper implements SubdumperInterface
{
    public function supports($var)
    {
        return true;
    }

    public function dump($var)
    {
        $internal = new InternalVarDumper();
        $result = $internal->dumpAsString($var);

        return new LinePart(\mb_substr($result, 0, -1));
    }
}
