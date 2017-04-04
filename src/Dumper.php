<?php

namespace Awesomite\VarDumper;

class Dumper
{
    /**
     * @param mixed... $args
     */
    public static function dump()
    {
        $dumper = new LightVarDumper(true, 1);
        foreach (func_get_args() as $arg) {
            $dumper->dump($arg);
        }
    }
}
