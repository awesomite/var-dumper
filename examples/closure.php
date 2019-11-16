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

require __DIR__ . \DIRECTORY_SEPARATOR . 'init.php';

$firstName = 'Mary';
$lastName = 'Watson';
$function = function ($a, $b) use ($firstName, $lastName) {
};

$dumper = new LightVarDumper();
$dumper->dump($function);

/*

Output:

object(Closure) #1 {[
        $name =>      “{closure}”
        $filename =>  “(...)/var-dumper/examples/closure.php”
        $startLine => 18
        $endLine =>   19
        $use =>
            array(2) {
                [firstName] => “Mary”
                [lastName] =>  “Watson”
            }
]}

*/
