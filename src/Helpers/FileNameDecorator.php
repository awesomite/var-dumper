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
final class FileNameDecorator
{
    const MAX_FILE_NAME_DEPTH = 3;

    /**
     * @param string $fileName
     *
     * @return string
     */
    public static function decorateFileName($fileName)
    {
        $relativeTo = $fileName;
        for ($i = 0; $i < static::MAX_FILE_NAME_DEPTH; $i++) {
            $relativeTo = \dirname($relativeTo);
        }

        if ($relativeTo === $fileName) {
            return $fileName;
        }

        $exploded = \explode(DIRECTORY_SEPARATOR, $fileName);
        $newParts = \array_slice($exploded, -static::MAX_FILE_NAME_DEPTH, static::MAX_FILE_NAME_DEPTH);
        \array_unshift($newParts, '(...)');

        return \implode(DIRECTORY_SEPARATOR, $newParts);
    }
}
