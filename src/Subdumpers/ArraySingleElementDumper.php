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
use Awesomite\VarDumper\LightVarDumper;

/**
 * @internal
 */
class ArraySingleElementDumper implements SubdumperInterface
{
    private $dumper;

    private $config;

    public function __construct(LightVarDumper $dumper, Config $config)
    {
        $this->dumper = $dumper;
        $this->config = $config;
    }

    public function supports(&$array)
    {
        return \is_array($array)
            && 1 === \count($array)
            && \array_key_exists(0, $array)
            && \is_string($array[0])
            && \mb_strlen($array[0]) <= $this->config->getMaxLineLength()
            && false === \mb_strpos($array[0], "\n")
        ;
    }

    public function dump(&$array)
    {
        echo 'array(1) {', \rtrim($this->dumper->getDump($array[0]), "\n"), "}\n";
    }
}
