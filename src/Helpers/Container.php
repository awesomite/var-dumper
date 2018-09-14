<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Helpers;

use Awesomite\VarDumper\Config\AbstractConfig;
use Awesomite\VarDumper\LightVarDumper;
use Awesomite\VarDumper\Objects\HasherFactory;
use Awesomite\VarDumper\Objects\HasherInterface;

/**
 * @internal
 */
final class Container
{
    private $references;

    private $depth;

    private $config;

    private $printNlOnEnd;

    private $dumper;

    private static $hasher;

    public function __construct(AbstractConfig $config, LightVarDumper $dumper)
    {
        $this->config = $config;
        $this->dumper = $dumper;
    }

    /**
     * @return AbstractConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Stack
     */
    public function getReferences()
    {
        return $this->references ?: $this->references = new Stack();
    }

    /**
     * @return IntValue
     */
    public function getDepth()
    {
        return $this->depth ?: $this->depth = new IntValue();
    }

    /**
     * @return BoolValue
     */
    public function getPrintNlOnEnd()
    {
        return $this->printNlOnEnd ?: $this->printNlOnEnd = new BoolValue(true);
    }

    /**
     * @return LightVarDumper
     */
    public function getDumper()
    {
        return $this->dumper;
    }

    /**
     * @return HasherInterface
     */
    public function getHasher()
    {
        return self::$hasher ?: self::$hasher = HasherFactory::create();
    }
}
