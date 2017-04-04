<?php

namespace Awesomite\VarDumper;

class Dumper
{
    public static function dump($var)
    {
        $dumper = new LightVarDumper(true, 1);
        foreach (func_get_args() as $arg) {
            $dumper->dump($arg);
        }
    }
}
