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
use Awesomite\VarDumper\Helpers\Container;
use Awesomite\VarDumper\Helpers\Strings;
use Awesomite\VarDumper\Helpers\Symbols;
use Awesomite\VarDumper\Strings\LinePart;
use Awesomite\VarDumper\Strings\Parts;

/**
 * @internal
 */
final class StringDumper extends AbstractDumper
{
    private static $inited = false;

    private static $whiteChars;

    public function __construct(Container $container)
    {
        parent::__construct($container);
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
        $isMultiLine = $len > $this->container->getConfig()->getMaxLineLength() || $containsNewLine;

        if (!$isMultiLine) {
            $visibleLen = $len;
            foreach (Strings::$replaceChars as $char => $replace) {
                $number = \mb_substr_count($var, $char);
                $visibleLen += $number * \mb_strlen($replace);
                if ($visibleLen > $this->container->getConfig()->getMaxLineLength()) {
                    $isMultiLine = true;
                    break;
                }
            }
        }

        if ($isMultiLine) {
            $withPrefix = true;
        }

        if ($len > $this->container->getConfig()->getMaxStringLength()) {
            $var = \mb_substr($var, 0, $this->container->getConfig()->getMaxStringLength());
            $withPrefix = true;
            $withSuffix = true;
        }

        $result = new Parts();
        $result->appendPart($firstLine = new LinePart(''));

        if ($withPrefix || $containsNewLine) {
            $firstLine->append("string({$len})");
        }
        if (!$isMultiLine) {
            if ($withPrefix) {
                $firstLine->append(' ');
            }
            $firstLine->append(Symbols::SYMBOL_LEFT_QUOT . $this->escapeWhiteChars($var) . Symbols::SYMBOL_RIGHT_QUOT);
            if ($withSuffix) {
                $firstLine->append('...');
            }
        } else {
            if ($withSuffix) {
                $var .= '...';
            }
            $subParts = $this->dumpMultiLine($var);
            $result->appendPart($subParts);
        }

        return $result;
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

    /**
     * @param $string
     *
     * @return Parts
     */
    private function dumpMultiLine($string)
    {
        $result = new Parts();
        $metalines = \explode("\n", $string);

        while (null !== $metaline = \array_shift($metalines)) {
            if (!empty($metalines)) {
                $metaline .= Symbols::SYMBOL_NEW_LINE;
            }
            foreach ($this->getLines($metaline) as $line) {
                $line = $this->container->getConfig()->getIndent() . Symbols::SYMBOL_CITE . ' ' . $line;
                $result->appendPart(new LinePart($line));
            }
        }

        return $result;
    }

    private function getLines($string)
    {
        $words = $this->explodeWords($string);
        $self = $this;
        $maxLen = $this->container->getConfig()->getMaxLineLength();

        return new CallbackIterator(function () use (&$words, $maxLen, $self) {
            while ($words) {
                $current = $self->escapeWhiteChars(\array_shift($words));

                if (\mb_strlen($current) > $maxLen) {
                    $next = \mb_substr($current, $maxLen);
                    $current = \mb_substr($current, 0, $maxLen);
                    \array_unshift($words, $next);

                    return $current;
                }

                while ($words) {
                    $nextWord = $self->escapeWhiteChars($words[0]);
                    if (\mb_strlen($current) + \mb_strlen($nextWord) > $maxLen) {
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

    /**
     * Public for php 5.3.
     *
     * @internal
     *
     * @param $string
     *
     * @return mixed
     */
    public function escapeWhiteChars($string)
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
        $data = array();
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
