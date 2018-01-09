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
use Awesomite\VarDumper\Helpers\Strings;
use Awesomite\VarDumper\LightVarDumper;

/**
 * @internal
 */
class ArrayBigDumper implements SubdumperInterface
{
    private $dumper;

    private $references;

    private $depth;

    private $config;

    public function __construct(
        LightVarDumper $dumper,
        Stack $references,
        IntValue $depth,
        Config $config
    ) {
        $this->dumper = $dumper;
        $this->references = $references;
        $this->depth = $depth;
        $this->config = $config;
    }

    public function supports($var)
    {
        return \is_array($var);
    }

    public function dump($array)
    {
        $this->depth->incr();
        $this->references->push($array);

        $count = \count($array);
        echo 'array(' . $count . ') {';

        if ($count > 0) {
            echo "\n";
            static::dumpBody($array, $this->config, $this->dumper);
        }

        echo '}' . "\n";

        $this->references->pop();
        $this->depth->decr();
    }

    public static function dumpBody(array $array, Config $config, LightVarDumper $dumper)
    {
        $indent = $config->getIndent();
        $limit = $config->getMaxChildren();
        $printer = new KeyValuePrinter();
        foreach ($array as $key => $value) {
            $key = Strings::prepareArrayKey($key);
            $valDump = $dumper->dumpAsString($value);
            $valDump = \mb_substr($valDump, 0, -1);
            if (false === \mb_strpos($valDump, "\n")) {
                $printer->add("{$indent}[{$key}] => ", $valDump, \mb_strlen("{$indent}[{$key}] => "));
            } else {
                $printer->flush();
                $valDump = \str_replace("\n", "\n{$indent}{$indent}", $valDump);
                echo "{$indent}[{$key}] =>\n{$indent}{$indent}$valDump\n";
            }

            if (!--$limit) {
                $printer->flush();
                if (\count($array) > $config->getMaxChildren()) {
                    echo "{$indent}(...)\n";
                }
                break;
            }
        }
        $printer->flush();
    }
}
