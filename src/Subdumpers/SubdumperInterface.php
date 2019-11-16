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

use Awesomite\VarDumper\Strings\PartInterface;

/**
 * @internal
 */
interface SubdumperInterface
{
    /**
     * @param $var
     *
     * @return bool
     */
    public function supports($var);

    /**
     * @throws VarNotSupportedException
     *
     * @return PartInterface
     */
    public function dump($var);
}
