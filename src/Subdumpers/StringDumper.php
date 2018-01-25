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
        $metalines = \explode("\n", $string);

        while (null !== $metaline = \array_shift($metalines)) {
            if (!empty($metalines)) {
                $metaline .= Symbols::SYMBOL_NEW_LINE;
            }
            foreach ($this->getLines($metaline) as $line) {
                echo "\n", $this->config->getIndent(), Symbols::SYMBOL_CITE, ' ', $line;
            }
        }
    }

    private function getLines($string)
    {
        $words = $this->explodeWords($string);
        $self = $this;
        $maxLen = $this->config->getMaxLineLength();

        while ($words) {
            $current = $self->escapeWhiteChars(\array_shift($words));

            if (\mb_strlen($current) > $maxLen) {
                $next = \mb_substr($current, $maxLen);
                $current = \mb_substr($current, 0, $maxLen);
                \array_unshift($words, $next);

                yield $current;
                continue;
            }

            while ($words) {
                $nextWord = $self->escapeWhiteChars($words[0]);
                if (\mb_strlen($current) + \mb_strlen($nextWord) > $maxLen) {
                    break;
                }
                $current .= $nextWord;
                \array_shift($words);
            }

            yield $current;
        }
    }

    private function escapeWhiteChars($string)
    {
        return Strings::prepareSingleLine($string);
    }

    private function explodeWords($string)
    {
        if ('' === $string) {
            return [''];
        }

        $words = [];
        while (false !== $pos = $this->getFirstWhiteCharPos($string)) {
            if (0 !== $pos) {
                $words[] = \mb_substr($string, 0, $pos);
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
        $data = [];
        foreach (self::$whiteChars as $char) {
            if (false !== $pos = \mb_strpos($string, $char)) {
                $data[] = $pos;
            }
        }
        if (false !== $pos = \mb_strpos($string, Symbols::SYMBOL_NEW_LINE)) {
            $data[] = $pos;
        }
        $regex = '/' . Strings::BINARY_CHAR_REGEX . '/';
        $split = \preg_split($regex, $string, 2);
        if (2 === \count($split)) {
            $data[] = \mb_strlen($split[0]);
        }

        return \count($data) ? \min($data) : false;
    }
}
