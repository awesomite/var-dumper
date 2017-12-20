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

/**
 * @internal
 */
class StringDumper implements SubdumperInterface
{
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
            echo "\n";
        } else {
            foreach (\explode("\n", $string) as $line) {
                while (true) {
                    if (\mb_strlen($line) > $this->config->getMaxLineLength()) {
                        $storage = \mb_substr($line, $this->config->getMaxLineLength());
                        $line = \mb_substr($line, 0, $this->config->getMaxLineLength());
                    } else {
                        $storage = '';
                    }
                    echo "\n", $this->indent, Symbols::SYMBOL_CITE, ' ', $line;
                    if ('' === $storage) {
                        break;
                    }
                    $line = $storage;
                }
            }
            if ($withSuffix) {
                echo '...';
            }
            echo "\n";
        }
    }
}
