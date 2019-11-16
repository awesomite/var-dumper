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

use Awesomite\VarDumper\Helpers\Strings;
use Awesomite\VarDumper\Strings\LinePart;

/**
 * @internal
 */
final class ArraySimpleViewDumper extends AbstractDumper
{
    const COUNT_LIMIT = 5;
    const KEY_LIMIT = 20;

    public function supports($var)
    {
        if (!\is_array($var)) {
            return false;
        }

        $count = \count($var);
        $limit = \min(static::COUNT_LIMIT, $this->container->getConfig()->getMaxChildren());

        if ($count > $limit || 0 === $count) {
            return false;
        }

        foreach ($var as $key => $value) {
            $key = Strings::prepareArrayKey($key);
            if (!\is_int($key) && \mb_strlen((string)$key) > static::KEY_LIMIT) {
                return false;
            }

            if (\is_array($value) && empty($value)) {
                continue;
            }

            if (\is_string($value)) {
                return false;
            }

            if (!\is_scalar($value) && null !== $value && !\is_resource($value)) {
                return false;
            }
        }

        return true;
    }

    public function dump($var)
    {
        $result = new LinePart('array(' . \count($var) . ') {');
        $i = 0;
        \end($var);
        $last = \key($var);
        \reset($var);
        $canSkipKey = true;
        foreach ($var as $key => $value) {
            $keyToDump = '';
            if (\is_string($key)) {
                $result->append('[' . Strings::prepareArrayKey($key) . '] => ');
                $canSkipKey = false;
            } elseif (!$canSkipKey || $key !== $i) {
                $result->append('[' . $key . '] => ');
                $canSkipKey = false;
            }
            $result->append((string)$this->container->getDumper()->dumpAsPart($value));
            if ($last !== $key) {
                $result->append(', ');
            }
            ++$i;
        }
        $result->append('}');

        return $result;
    }
}
