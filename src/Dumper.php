<?php

namespace Awesomite\VarDumper;

/**
 * The following class is just an example how to use LightVarDumper
 */
class Dumper
{
    /**
     * @param mixed ...$args
     */
    public static function dump()
    {
        $dumper = new LightVarDumper(true, 1);
        foreach (func_get_args() as $arg) {
            $dumper->dump($arg);
        }
    }
}
