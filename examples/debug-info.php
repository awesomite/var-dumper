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

/**
 * @internal
 */
class MyClass
{
    public function __debugInfo()
    {
        return array(
            'key'=> 'value',
            'some' => 'output',
        );
    }
}

$dumper = new LightVarDumper();
$dumper->dump(new MyClass());

/*

Output:

object(MyClass) #1 (1) {
    public $__debugInfo() =>
        array(2) {
            [key] =>  “value”
            [some] => “output”
        }
}

*/
