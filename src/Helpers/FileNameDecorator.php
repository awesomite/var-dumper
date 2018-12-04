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
     * @param string   $fileName
     * @param null|int $maxDepth
     *
     * @return string
     */
    public static function decorateFileName($fileName, $maxDepth = null)
    {
        $maxDepth = \is_null($maxDepth)
            ? static::MAX_FILE_NAME_DEPTH
            : $maxDepth;

        $relativeTo = $fileName;
        for ($i = 0; $i < $maxDepth; $i++) {
            $relativeTo = \dirname($relativeTo);
        }

        if ($relativeTo === $fileName) {
            return $fileName;
        }

        $exploded = \explode(DIRECTORY_SEPARATOR, $fileName);
        $newParts = \array_slice($exploded, -$maxDepth, $maxDepth);
        \array_unshift($newParts, '(...)');

        return \implode(DIRECTORY_SEPARATOR, $newParts);
    }
}
