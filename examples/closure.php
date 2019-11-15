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

$name = 'Jane';
$function = function ($a, $b) use ($name) {
};

$dumper = new LightVarDumper();
$dumper->dump($function);

/*

Output:

object(Closure) #1 {[
        $name =>      “{closure}”
        $filename =>  “(...)/var-dumper/examples/closure.php”
        $startLine => 17
        $endLine =>   18
        $use =>
            array(1) {
                [name] => “Jane”
            }
]}

*/
