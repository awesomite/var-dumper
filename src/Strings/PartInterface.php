<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Strings;

/**
 * @internal
 */
interface PartInterface
{
    public function isMultiLine();

    public function addIndent($indent);

    public function __toString();
}
