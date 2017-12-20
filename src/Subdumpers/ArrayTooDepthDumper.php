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

/**
 * @internal
 */
class ArrayTooDepthDumper implements SubdumperInterface
{
    private $config;
    
    private $depth;
    
    public function __construct(IntValue $depth, Config $config)
    {
        $this->depth = $depth;
        $this->config = $config;
    }

    public function supports(&$var)
    {
        return \is_array($var) && $this->depth->getValue() === $this->config->getMaxDepth();
    }

    public function dump(&$var)
    {
        $c = \count($var);
        echo 'array(', $c, ') {', ($c ? '...' : ''), "}\n";
    }
}
