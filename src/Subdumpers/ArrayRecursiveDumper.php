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

use Awesomite\VarDumper\Helpers\Container;

/**
 * @internal
 */
class ArrayRecursiveDumper extends AbstractDumper
{
    private $references;

    private static $inited = false;

    private static $canCompareArrays = null;

    public function __construct(Container $container)
    {
        parent::__construct($container);
        self::init();
    }

    public function supports($var)
    {
        return self::$canCompareArrays && $this->container->getReferences()->in($var);
    }

    public function dump($array)
    {
        echo 'RECURSIVE array(' . \count($array) . ")";
    }

    private static function init()
    {
        if (self::$inited) {
            return;
        }

        self::$canCompareArrays = self::canCompareArrayReferences();
        self::$inited = true;
    }

    /**
     * Code:
     *
     * $a = array();
     * $b = &$a;
     * $a[] = $b;
     * var_dump(in_array($b, array($a), true));
     *
     * throws fatal error "Nesting level too deep - recursive dependency?"
     * in PHP <= 5.3.14 || (PHP >= 5.4 && PHP <= 5.4.4)
     *
     * @codeCoverageIgnore
     */
    private static function canCompareArrayReferences()
    {
        if (\version_compare(PHP_VERSION, '5.4.5') >= 0) {
            return true;
        }

        // 5.4.* && < 5.4.5
        if (PHP_MINOR_VERSION === 4) {
            return false;
        }

        return \version_compare(PHP_VERSION, '5.3.15') >= 0;
    }
}
