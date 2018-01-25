<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Helpers;

/**
 * @internal
 */
class Strings
{
    const BINARY_CHAR_REGEX = '[\x00-\x1F\x7F]';

    public static $replaceChars
        = [
            "\t"   => '\t',
            "\r"   => '\r',
            "\0"   => '\0',
            "\v"   => '\v',
            "\033" => '\e',
            "\f"   => '\f',
        ];

    public static function prepareArrayKey($input)
    {
        return self::convertNonPrintableChars($input, true);
    }

    public static function prepareSingleLine($input)
    {
        return self::convertNonPrintableChars($input);
    }

    /**
     * Excludes spaces
     *
     * @param      $input
     * @param bool $withNewLine
     *
     * @return mixed
     */
    private static function convertNonPrintableChars($input, $withNewLine = false)
    {
        $result = \str_replace(\array_keys(self::$replaceChars), \array_values(self::$replaceChars), $input);
        if ($withNewLine) {
            $result = \str_replace("\n", Symbols::SYMBOL_NEW_LINE, $input);
        }

        $callable = function ($chars) {
            $result = '';
            $i = 0;
            $chars = $chars[$i];
            do {
                $result .= \sprintf('\x%02X', \ord($chars[$i]));
            } while (isset($chars[++$i]));

            return $result;
        };

        return \preg_replace_callback('/' . static::BINARY_CHAR_REGEX . '+/', $callable, $result);
    }
}
