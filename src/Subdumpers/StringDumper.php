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
use Awesomite\VarDumper\Helpers\Strings;
use Awesomite\VarDumper\Helpers\Symbols;

/**
 * @internal
 */
class StringDumper implements SubdumperInterface
{
    private static $inited = false;

    private static $whiteChars;

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        self::init();
    }

    public function supports($var)
    {
        return \is_string($var);
    }

    public function dump($var)
    {
        $len = \mb_strlen($var);
        $withPrefix = false;
        $withSuffix = false;

        $containsNewLine = false !== \mb_strpos($var, "\n");
        $isMultiLine = $len > $this->config->getMaxLineLength() || $containsNewLine;

        if (!$isMultiLine) {
            $visibleLen = $len;
            foreach (Strings::$replaceChars as $char => $replace) {
                $number = \mb_substr_count($var, $char);
                $visibleLen += $number * \mb_strlen($replace);
                if ($visibleLen > $this->config->getMaxLineLength()) {
                    $isMultiLine = true;
                    break;
                }
            }
        }

        if ($isMultiLine) {
            $withPrefix = true;
        }

        if ($len > $this->config->getMaxStringLength()) {
            $var = \mb_substr($var, 0, $this->config->getMaxStringLength());
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
            echo Symbols::SYMBOL_LEFT_QUOT, $this->escapeWhiteChars($var), Symbols::SYMBOL_RIGHT_QUOT;
            if ($withSuffix) {
                echo '...';
            }
        } else {
            if ($withSuffix) {
                $var .= '...';
            }
            $this->dumpMultiLine($var);
        }
        echo "\n";
    }

    private static function init()
    {
        if (self::$inited) {
            return;
        }

        self::$whiteChars = \array_keys(Strings::$replaceChars);
        self::$whiteChars[] = ' ';

        self::$inited = true;
    }

    private function dumpMultiLine($string)
    {
        foreach (\explode("\n", $string) as $metaline) {
            foreach ($this->getLines($metaline) as $line) {
                echo "\n", $this->config->getIndent(), Symbols::SYMBOL_CITE, ' ', $line;
            }
        }
    }

    private function getLines($string)
    {
        $config = $this->config;
        $words = $this->explodeWords($string);

        return new CallbackIterator(function () use (&$words, $config) {
            while ($words) {
                $current = Strings::prepareSingleLine(\array_shift($words));

                if (\mb_strlen($current) > $config->getMaxLineLength()) {
                    $next = \mb_substr($current, $config->getMaxLineLength());
                    $current = \mb_substr($current, 0, $config->getMaxLineLength());
                    \array_unshift($words, $next);

                    return $current;
                }

                while ($words) {
                    $nextWord = Strings::prepareSingleLine($words[0]);
                    if (\mb_strlen($current) + \mb_strlen($nextWord) > $config->getMaxLineLength()) {
                        break;
                    }
                    $current .= $nextWord;
                    \array_shift($words);
                }

                return $current;
            }

            CallbackIterator::stopIterate();
        });
    }

    private function escapeWhiteChars($string)
    {
        return Strings::prepareSingleLine($string);
    }

    private function explodeWords($string)
    {
        if ('' === $string) {
            return array('');
        }

        $words = array();
        while (false !== $pos = $this->getFirstWhiteCharPos($string)) {
            $wordA = \mb_substr($string, 0, $pos);
            if ('' !== $words) {
                $words[] = $wordA;
            }
            $words[] = \mb_substr($string, $pos, 1);
            $string = \mb_substr($string, $pos + 1);
        }
        if ('' !== $string) {
            $words[] = $string;
        }

        return $words;
    }

    private function getFirstWhiteCharPos($string)
    {
        $data = array();
        foreach (self::$whiteChars as $char) {
            if (false !== $pos = \mb_strpos($string, $char)) {
                $data[] = $pos;
            }
        }
        $regex = '/' . Strings::BINARY_CHAR_REGEX . '/';
        $split = \preg_split($regex, $string, 2);
        if (2 === \count($split)) {
            $data[] = \mb_strlen($split[0]);
        }

        return \count($data) ? \min($data) : false;
    }
}
