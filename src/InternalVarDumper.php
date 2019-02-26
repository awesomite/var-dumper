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

use Awesomite\VarDumper\Helpers\FileNameDecorator;

class InternalVarDumper implements VarDumperInterface
{
    protected $displayPlaceInCode;

    private $shift;

    private $maxFileNameDepth = LightVarDumper::DEFAULT_MAX_FILENAME_DEPTH;

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
        \ini_set($iniKey, '0');

        if ($this->displayPlaceInCode) {
            echo $this->dumpPlaceInCodeAsString(0);
        }
        \var_dump($var);

        if (false !== $previousVal) {
            \ini_set($iniKey, $previousVal);
        }
    }

    public function dumpAsString($var)
    {
        \ob_start();
        ++$this->shift;
        $this->dump($var);
        --$this->shift;
        $result = \ob_get_contents();
        \ob_end_clean();

        return $result;
    }

    /**
     * Feature does not work on Windows.
     *
     * @param int $depth to remove limit, pass 0 or negative value
     *
     * @return $this
     */
    public function setMaxFileNameDepth($depth)
    {
        $this->maxFileNameDepth = (int)$depth;

        return $this;
    }

    final protected function dumpPlaceInCodeAsString($number)
    {
        $options = \version_compare(PHP_VERSION, '5.3.6') >= 0 ? DEBUG_BACKTRACE_IGNORE_ARGS : false;
        $stackTrace = \debug_backtrace($options);
        $num = 1 + $number + $this->shift;

        // @codeCoverageIgnoreStart
        if (!isset($stackTrace[$num])) {
            return '';
        }
        // @codeCoverageIgnoreEnd

        $step = $stackTrace[$num];

        if (isset($step['file']) && !empty($step['file'])) {
            return FileNameDecorator::decorateFileName($step['file'], $this->maxFileNameDepth) . (isset($step['line']) ? ':' . $step['line'] : '') . ":\n";
        }

        return '';
    }
}
