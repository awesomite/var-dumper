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
use Awesomite\VarDumper\Helpers\Stack;
use Awesomite\VarDumper\LightVarDumper;

/**
 * @internal
 */
abstract class AbstractObjectBigDumper extends AbstractObjectDumper
{
    protected $dumper;

    protected $references;

    protected $indent;

    protected $depth;

    protected $config;

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
        parent::__construct();
    }
}
