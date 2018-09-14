<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!\function_exists('is_iterable')) {
    function is_iterable($var)
    {
        return \is_array($var) || $var instanceof \Traversable;
    }
}
