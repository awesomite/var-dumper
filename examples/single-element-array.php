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

$dumper = new LightVarDumper();
$dumper->dump(array('Hello world! :)'));

/*

Output:

array(1) {“Hello world! :)”}

*/
