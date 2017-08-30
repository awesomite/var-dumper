<?php

use Awesomite\VarDumper\LightVarDumper;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

$dumper = new LightVarDumper();
$dumper
    ->setMaxChildren(3)
    ->setMaxDepth(3)
    ->setMaxStringLength(5);

class Foo
{
    private $self;

    public function __construct()
    {
        $this->self = $this;
    }
}

class Bar extends Foo
{
    public $propBar = 'Hello world!';
}

class Foobar extends Bar
{
    protected $propFooBar;

    public function __construct()
    {
        parent::__construct();
        $this->propFooBar = array(
            'First' => new stdClass(),
            'Second' => 2,
            'Third' => array(array(array())),
            'Fourth' => 4,
        );
    }
}


$object = new Foobar();

$dumper->dump($object);

/*

Output:

object(Foobar) #1 (2) {
    protected $propFooBar =>
        array(4) {
            [First] =>  object(stdClass) #2 (0) {}
            [Second] => 2
            [Third] =>
                array(1) {
                    [0] => array(1) {...}
                }
            (...)
        }
    public $propBar => string(12) “Hello”...
}

*/
