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

use Awesomite\VarDumper\Properties\Properties;
use Awesomite\VarDumper\Properties\PropertyInterface;

/**
 * @internal
 */
abstract class AbstractObjectDumper extends AbstractDumper
{
    /**
     * @param $object
     *
     * @return PropertyInterface[]
     */
    protected function getProperties($object)
    {
        $propertiesIterator = new Properties($object);

        return $propertiesIterator->getProperties();
    }

    protected function getClassName($object)
    {
        $class = \get_class($object);

        // @see https://github.com/facebook/hhvm/issues/7868
        // @codeCoverageIgnoreStart
        if (\defined('HHVM_VERSION') && $object instanceof \Closure) {
            $class = 'Closure';
        }

        // @codeCoverageIgnoreEnd

        return $class;
    }
}
