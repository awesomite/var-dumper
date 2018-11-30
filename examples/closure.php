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

require __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

global $argc, $argv;
$function = function ($a, $b) use ($argc, $argv) {
};

$dumper = new LightVarDumper();
$dumper->dump($function);

/*

Output:

object(Closure) #3 {[
        $name =>      “{closure}”
        $filename =>  “(...)/examples/closure.php”
        $startLine => 17
        $endLine =>   18
        $use =>
            array(2) {
                [argc] => 1
                [argv] => array(1) {“examples/closure.php”}
            }
]}

*/
