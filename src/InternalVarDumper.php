<?php

namespace Awesomite\VarDumper;

class InternalVarDumper implements VarDumperInterface
{
    protected $displayPlaceInCode;
    
    private $shift;
    
    public function __construct($displayPlaceInCode = false, $stepShift = 0)
    {
        $this->displayPlaceInCode = $displayPlaceInCode;
        $this->shift = $stepShift;
    }

    public function dump($var)
    {
        $iniKey = 'xdebug.overload_var_dump';
        $previousVal = ini_get($iniKey);
        ini_set($iniKey, 0);

        if ($this->displayPlaceInCode) {
            $this->dumpPlaceInCode(0);
        }
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
    
    protected function dumpPlaceInCode($number)
    {
        $options = version_compare(PHP_VERSION, '5.3.6') >= 0 ? DEBUG_BACKTRACE_IGNORE_ARGS : false;
        $stackTrace = debug_backtrace($options);
        $num = 1 + $number + $this->shift;

        // @codeCoverageIgnoreStart
        if (!isset($stackTrace[$num])) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $step = $stackTrace[$num];

        if (isset($step['file']) && $step['file']) {
            echo $step['file'] . (isset($step['line']) ? ':' . $step['line'] : '') . ":\n";
        }
    }
}