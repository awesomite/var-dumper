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

use Awesomite\VarDumper\Config\Config;
use Awesomite\VarDumper\Helpers\Symbols;
use Awesomite\VarDumper\LightVarDumper;

/**
 * @internal
 */
class ArraySimpleView implements SubdumperInterface
{
    const COUNT_LIMIT = 5;
    const KEY_LIMIT   = 20;

    private $dumper;

    private $config;

    public function __construct(LightVarDumper $dumper, Config $config)
    {
        $this->dumper = $dumper;
        $this->config = $config;
    }

    public function supports(&$var)
    {
        if (!\is_array($var)) {
            return false;
        }

        $count = \count($var);
        $limit = \min(static::COUNT_LIMIT, $this->config->getMaxChildren());

        if ($count > $limit || 0 === $count) {
            return false;
        }

        foreach ($var as $key => $value) {
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

    public function dump(&$var)
    {
        echo 'array(', \count($var), ') {';
        $i = 0;
        \end($var);
        $last = \key($var);
        \reset($var);
        $canSkipKey = true;
        foreach ($var as $key => $value) {
            $keyToDump = '';
            if (\is_string($key)) {
                $keyToDump = '[' . \str_replace("\n", Symbols::SYMBOL_NEW_LINE, $key) . '] => ';
                $canSkipKey = false;
            } elseif (!$canSkipKey || $key !== $i) {
                $keyToDump = '[' . $key . '] => ';
                $canSkipKey = false;
            }
            echo $keyToDump, \rtrim($this->dumper->getDump($value), "\n");
            if ($last !== $key) {
                echo ', ';
            }
            ++$i;
        }
        echo "}\n";
    }
}
