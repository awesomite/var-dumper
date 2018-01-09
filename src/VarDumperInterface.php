<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper;

interface VarDumperInterface
{
    public function dump($var);

    /**
     * @deprecated
     *
     * @param mixed $var
     *
     * @return string
     */
    public function getDump($var);

    /**
     * @param mixed $var
     *
     * @return string
     */
    public function dumpAsString($var);
}
