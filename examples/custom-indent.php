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

$object = new stdClass();
$object->foo = 'bar';

$array = array(
    1,
    2.5,
    array(
        \M_PI,
        array(
            $object,
            \range(1, 5),
        ),
    ),
);

$dumper = new LightVarDumper();
$dumper->setIndent('····');
$dumper->dump($array);

/*

Output:

array(3) {
····[0] => 1
····[1] => 2.5
····[2] =>
········array(2) {
············[0] => M_PI
············[1] =>
················array(2) {
····················[0] =>
························object(stdClass) #1 (1) {
····························$foo => “bar”
························}
····················[1] => array(5) {1, 2, 3, 4, 5}
················}
········}
}

*/
