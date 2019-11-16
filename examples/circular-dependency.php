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

$first = new \stdClass();
$second = new stdClass();

$first->next = $second;
$second->next = $first;

$dumper = new LightVarDumper();
$dumper->dump($first);

/*

Output:

object(stdClass) #1 (1) {
    $next =>
        object(stdClass) #2 (1) {
            $next => RECURSIVE object(stdClass) #1
        }
}

*/
