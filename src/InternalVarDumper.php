<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper;

class InternalVarDumper implements VarDumperInterface
{
    protected $displayPlaceInCode;

    private $shift;

    /**
     * @param bool $displayPlaceInCode true whenever dumper should print also information about file and line
     * @param int  $stepShift          nesting level of method "dump", it is used whenever $displayPlaceInCode is equal to true
     */
    public function __construct($displayPlaceInCode = false, $stepShift = 0)
    {
        $this->displayPlaceInCode = $displayPlaceInCode;
        $this->shift = $stepShift;
    }

    public function dump($var)
    {
        $iniKey = 'xdebug.overload_var_dump';
        $previousVal = \ini_get($iniKey);
        \ini_set($iniKey, 0);

        if ($this->displayPlaceInCode) {
            $this->dumpPlaceInCode(0);
        }
        \var_dump($var);

        \ini_set($iniKey, $previousVal);
    }

    public function dumpAsString($var)
    {
        \ob_start();
        $this->dump($var);
        $result = \ob_get_contents();
        \ob_end_clean();

        return $result;
    }

    public function getDump($var)
    {
        return $this->dumpAsString($var);
    }

    protected function dumpPlaceInCode($number)
    {
        $options = \version_compare(PHP_VERSION, '5.3.6') >= 0 ? DEBUG_BACKTRACE_IGNORE_ARGS : false;
        $stackTrace = \debug_backtrace($options);
        $num = 1 + $number + $this->shift;

        // @codeCoverageIgnoreStart
        if (!isset($stackTrace[$num])) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $step = $stackTrace[$num];

        if (isset($step['file']) && !empty($step['file'])) {
            echo $step['file'] . (isset($step['line']) ? ':' . $step['line'] : '') . ":\n";
        }
    }
}
