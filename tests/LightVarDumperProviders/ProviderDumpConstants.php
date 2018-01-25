<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class ProviderDumpConstants implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = [];

        $result['M_PI'] = [M_PI, "M_PI\n"];
        $result['M_EULER'] = [M_EULER, "M_EULER\n"];
        $result['INF'] = [INF, "INF\n"];
        $result['NAN'] = [NAN, "NAN\n"];
        $result['PHP_INT_MIN'] = [PHP_INT_MIN, "PHP_INT_MIN\n"];
        $result['PHP_INT_MAX'] = [PHP_INT_MAX, "PHP_INT_MAX\n"];

        if (\defined('PHP_FLOAT_EPSILON')) {
            $result['PHP_FLOAT_EPSILON'] = [PHP_FLOAT_EPSILON, "PHP_FLOAT_EPSILON\n"];
        }

        return new \ArrayIterator($result);
    }
}
