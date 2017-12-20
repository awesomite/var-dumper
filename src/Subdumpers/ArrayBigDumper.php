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
use Awesomite\VarDumper\Helpers\IntValue;
use Awesomite\VarDumper\Helpers\KeyValuePrinter;
use Awesomite\VarDumper\Helpers\Stack;
use Awesomite\VarDumper\Helpers\Symbols;
use Awesomite\VarDumper\LightVarDumper;

/**
 * @internal
 */
class ArrayBigDumper implements SubdumperInterface
{
    private $dumper;

    private $references;

    private $indent;

    private $depth;
    
    private $config;

    public function __construct(
        LightVarDumper $dumper,
        Stack $references,
        $indent,
        IntValue $depth,
        Config $config
    ) {
        $this->dumper = $dumper;
        $this->references = $references;
        $this->indent = $indent;
        $this->depth = $depth;
        $this->config = $config;
    }

    public function supports(&$var)
    {
        return \is_array($var);
    }

    public function dump(&$array)
    {
        $this->depth->incr();
        $this->references->push($array);

        $count = \count($array);
        echo 'array(' . $count . ') {';

        if ($count > 0) {
            echo "\n";
            $this->dumpBody($array);
        }

        echo '}' . "\n";

        $this->references->pop();
        $this->depth->decr();
    }
    
    private function dumpBody(array &$array)
    {
        $limit = $this->config->getMaxChildren();
        $printer = new KeyValuePrinter();
        foreach ($array as $key => $value) {
            $key = \str_replace("\n", Symbols::SYMBOL_NEW_LINE, $key);
            $valDump = $this->dumper->getDump($value);
            $valDump = \mb_substr($valDump, 0, -1);
            if (false === \mb_strpos($valDump, "\n")) {
                $printer->add("{$this->indent}[{$key}] => ", $valDump, \mb_strlen("{$this->indent}[{$key}] => "));
            } else {
                $printer->flush();
                $valDump = \str_replace("\n", "\n{$this->indent}{$this->indent}", $valDump);
                echo "{$this->indent}[{$key}] =>\n{$this->indent}{$this->indent}$valDump\n";
            }

            if (!--$limit) {
                $printer->flush();
                if (\count($array) > $this->config->getMaxChildren()) {
                    echo "{$this->indent}(...)\n";
                }
                break;
            }
        }
        $printer->flush();
    }
}
