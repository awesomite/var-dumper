<?php

namespace Awesomite\VarDumper;

class InternalVarDumper implements VarDumperInterface
{
    public function dump($var)
    {
        $iniKey = 'xdebug.overload_var_dump';
        $previousVal = ini_get($iniKey);
        ini_set($iniKey, 0);

        var_dump($var);

        ini_set($iniKey, $previousVal);
    }

    public function getDump($var)
    {
        ob_start();
        $this->dump($var);
        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }
}