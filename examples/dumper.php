<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Awesomite\VarDumper\LightVarDumper;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

/**
 * The following class is just an example how to use LightVarDumper.
 */
class Dumper
{
    /**
     * @param mixed ...$args
     */
    public static function dump()
    {
        $dumper = new LightVarDumper(true, 1);
        foreach (\func_get_args() as $arg) {
            $dumper->dump($arg);
        }
    }
}

$obj1 = new stdClass();
$obj2 = new stdClass();
$obj2->obj1 = $obj1;

Dumper::dump($obj1, $obj2);

/*

Output:

(...)/var-dumper/examples/dumper.php:37:
object(stdClass) #1 (0) {}
(...)/var-dumper/examples/dumper.php:37:
object(stdClass) #2 (1) {
    $obj1 => object(stdClass) #1 (0) {}
}

*/
