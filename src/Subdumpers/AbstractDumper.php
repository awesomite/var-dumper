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

use Awesomite\VarDumper\Helpers\Container;

/**
 * @internal
 */
abstract class AbstractDumper implements SubdumperInterface
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
