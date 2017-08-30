<?php

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
