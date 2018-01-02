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
class ObjectTooDepthArrayDumper extends AbstractObjectDumper
{
    private $depth;

    private $config;

    public function __construct(IntValue $depth, Config $config)
    {
        $this->depth = $depth;
        $this->config = $config;
        parent::__construct();
    }

    public function supports($var)
    {
        return \is_object($var) && $this->depth->getValue() === $this->config->getMaxDepth();
    }

    public function dump($object)
    {
        $class = $this->getClassName($object);
        $properties = $this->getProperties($object);

        echo 'object(', $class, ') #', self::$hasher->getHashId($object), ' (', \count($properties), ') {';
        echo \count($properties) ? '...' : '';
        echo "}\n";
    }
}
