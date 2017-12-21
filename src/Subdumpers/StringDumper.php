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

use Awesomite\Iterators\CallbackIterator;
use Awesomite\VarDumper\Config\Config;
use Awesomite\VarDumper\Helpers\Symbols;

/**
 * @internal
 */
class StringDumper implements SubdumperInterface
{
    /**
     * @internal Public for php 5.3
     */
    public static $whiteChars = array(
        ' ',
        "\t",
        "\n",
        "\r",
        "\0",
        "\x0B",
    );

    private $indent;

    private $config;

    public function __construct($indent, Config $config)
    {
        $this->indent = $indent;
        $this->config = $config;
    }

    public function supports(&$var)
    {
        return \is_string($var);
    }

    public function dump(&$var)
    {
        $string = $var;
        $len = \mb_strlen($string);
        $withPrefix = false;
        $withSuffix = false;

        $containsNewLine = false !== \mb_strpos($string, "\n");
        $isMultiLine = $len > $this->config->getMaxLineLength() || $containsNewLine;

        if ($isMultiLine) {
            $withPrefix = true;
        }

        if ($len > $this->config->getMaxStringLength()) {
            $string = \mb_substr($string, 0, $this->config->getMaxStringLength());
            $withPrefix = true;
            $withSuffix = true;
        }

        if ($withPrefix || $containsNewLine) {
            echo "string({$len})";
        }
        if (!$isMultiLine) {
            if ($withPrefix) {
                echo ' ';
            }
            echo Symbols::SYMBOL_LEFT_QUOT, $string, Symbols::SYMBOL_RIGHT_QUOT;
            if ($withSuffix) {
                echo '...';
            }
        } else {
            if ($withSuffix) {
                $string .= '...';
            }
            $this->dumpMultiLine($string);
        }
        echo "\n";
    }

    private function dumpMultiLine(&$string)
    {
        foreach (\explode("\n", $string) as $metaline) {
            foreach ($this->getLines($metaline) as $line) {
                echo "\n", $this->indent, Symbols::SYMBOL_CITE, ' ', $line;
            }
        }
    }

    private function getLines(&$string)
    {
        $firstIteration = true;
        $config = $this->config;
        $self = $this;

        return new CallbackIterator(function () use (&$string, &$firstIteration, &$config, $self) {
            while ('' !== $string) {
                $current = \mb_substr($string, 0, $config->getMaxLineLength());
                $next = \mb_substr($string, $config->getMaxLineLength());

                $toCheck = array(
                    \mb_substr($current, -1),
                    \mb_substr($next, 0, 1),
                );
                $dividedByWhite = '' === $next;
                foreach ($toCheck as $char) {
                    if ($dividedByWhite |= \in_array($char, $self::$whiteChars, true)) {
                        break;
                    }
                }

                if (!$dividedByWhite && $pos = $self->getLastWhiteCharPos($current)) {
                    $next = \mb_substr($current, $pos) . $next;
                    $current = \mb_substr($current, 0, $pos);
                }

                $firstIteration = false;
                $string = $next;

                return $current;
            }

            if ($firstIteration) {
                $firstIteration = false;

                return '';
            }

            CallbackIterator::stopIterate();
        });
    }

    /**
     * @internal Public for php 5.3
     *
     * @param $string
     *
     * @return bool|mixed
     */
    public function getLastWhiteCharPos(&$string)
    {
        $data = array();
        foreach (self::$whiteChars as $char) {
            if (false !== $pos = \mb_strrpos($string, $char)) {
                $data[] = $pos + 1;
            }
        }

        return \count($data) ? \max($data) : false;
    }
}
