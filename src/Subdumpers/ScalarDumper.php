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

/**
 * @internal
 */
class ScalarDumper implements SubdumperInterface
{
    private static $floatMapping
        = [
            'M_PI'       => M_PI,
            'M_E'        => M_E,
            'M_LOG2E'    => M_LOG2E,
            'M_LOG10E'   => M_LOG10E,
            'M_LN2'      => M_LN2,
            'M_LN10'     => M_LN10,
            'M_PI_2'     => M_PI_2,
            'M_PI_4'     => M_PI_4,
            'M_1_PI'     => M_1_PI,
            'M_2_PI'     => M_2_PI,
            'M_SQRTPI'   => M_SQRTPI,
            'M_2_SQRTPI' => M_2_SQRTPI,
            'M_SQRT2'    => M_SQRT2,
            'M_SQRT3'    => M_SQRT3,
            'M_SQRT1_2'  => M_SQRT1_2,
            'M_LNPI'     => M_LNPI,
            'M_EULER'    => M_EULER,
        ];

    private static $intMapping
        = [
            PHP_INT_MAX => 'PHP_INT_MAX',
        ];

    private static $inited = false;

    public function __construct()
    {
        self::init();
    }

    public function supports($scalar)
    {
        return \is_scalar($scalar) && !\is_string($scalar);
    }

    public function dump($scalar)
    {
        if (\is_float($scalar)) {
            foreach (self::$floatMapping as $key => $value) {
                if ($value === $scalar) {
                    echo $key, "\n";

                    return;
                }
            }
        }

        if (\is_int($scalar) && \array_key_exists($scalar, self::$intMapping)) {
            echo self::$intMapping[$scalar], "\n";

            return;
        }

        echo \var_export($scalar, true), "\n";
    }

    private static function init()
    {
        if (self::$inited) {
            return;
        }

        $php72Constants = [
            'PHP_FLOAT_EPSILON',
            'PHP_FLOAT_MIN',
            'PHP_FLOAT_MAX',
        ];

        foreach ($php72Constants as $constant) {
            if (\defined($constant)) {
                self::$floatMapping[$constant] = \constant($constant);
            }
        }

        self::$intMapping[~PHP_INT_MAX] = 'PHP_INT_MIN';
        self::$inited = true;
    }
}
