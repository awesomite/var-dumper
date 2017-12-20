<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @internal
 *
 * @param array $array
 *
 * @return array|bool
 */
function awesomite_each(array &$array)
{
    if (false !== $arg = \current($array)) {
        $i = \key($array);
        \next($array);

        return array(
            1 => $arg,
            'value' => $arg,
            0 => $i,
            'key' => $i,
        );
    }

    return false;
}
