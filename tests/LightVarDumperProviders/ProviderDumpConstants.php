<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class ProviderDumpConstants implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = array();

        $result['M_PI'] = array(M_PI, "M_PI\n");
        $result['M_EULER'] = array(M_EULER, "M_EULER\n");
        $result['INF'] = array(INF, "INF\n");
        $result['NAN'] = array(NAN, "NAN\n");
        if (defined('PHP_INT_MIN')) {
            $result['PHP_INT_MIN'] = array(PHP_INT_MIN, "PHP_INT_MIN\n");
        }
        $result['PHP_INT_MAX'] = array(PHP_INT_MAX, "PHP_INT_MAX\n");

        return new \ArrayIterator($result);
    }
}
