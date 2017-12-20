<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
abstract class AbstractProperties implements PropertiesInterface
{
    protected $object;

    protected function getDeclaredProperties()
    {
        $reflection = new \ReflectionObject($this->object);

        return $reflection->getProperties();
    }
}
