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

require_once __DIR__ . \DIRECTORY_SEPARATOR . 'init.php';

$array = new ArrayObject(
    array(
        'firstname' => 'Jane',
        'lastname' => 'Watson',
        'nationality' => 'American',
    ),
    ArrayObject::ARRAY_AS_PROPS
);

$dumper = new LightVarDumper();
$dumper->dump($array);

/*

Output:

object(ArrayObject) #3 (3) {
    private $storage =>
        array(3) {
            [firstname] =>   “Jane”
            [lastname] =>    “Watson”
            [nationality] => “American”
        }
    private $flags =>         2
    private $iteratorClass => “ArrayIterator”
}

*/
