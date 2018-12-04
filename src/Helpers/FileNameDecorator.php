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
     * Feature does not work on Windows
     *
     * @param string   $fileName
     * @param null|int $maxDepth
     *
     * @return string
     */
    public static function decorateFileName($fileName, $maxDepth = null)
    {
        // @codeCoverageIgnoreStart
        if (DIRECTORY_SEPARATOR === '\\') {
            return $fileName;
        }
        // @codeCoverageIgnoreEnd

        if ('/' !== \mb_substr($fileName, 0, 1)) {
            return $fileName; // only absolute paths allowed
        }

        if ('/' === $fileName) {
            return $fileName;
        }

        $maxDepth = \is_null($maxDepth)
            ? static::MAX_FILE_NAME_DEPTH
            : $maxDepth;

        if ($maxDepth < 1) {
            throw new \InvalidArgumentException('Parameter $maxDepth must be greater than or equal to 1');
        }

        $exploded = \explode(DIRECTORY_SEPARATOR, $fileName);

        if (\count($exploded) -1 <= $maxDepth) {
            return $fileName;
        }

        $newParts = \array_slice($exploded, -$maxDepth, $maxDepth);
        \array_unshift($newParts, '(...)');

        return \implode(DIRECTORY_SEPARATOR, $newParts);
    }
}
