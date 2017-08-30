<?php

use Awesomite\VarDumper\LightVarDumper;

require __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

/**
 * @internal
 */
class ExampleData
{
    public $recursion;

    protected $emptyArray = array();

    private $lipsum;

    private $lipsum2;

    public function __construct()
    {
        $this->prepareLipsum();
        $this->prepareArray();
        $this->prepareRecursion();
    }

    private function prepareLipsum()
    {
        $this->lipsum = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'lorem-ipsum.txt');
        $this->lipsum2 = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'lorem-ipsum2.txt');
    }

    private function prepareArray()
    {
        $array = array();
        for ($i = 0; $i < 10; $i++) {
            $array[implode('', range(0, $i))] = $i;
        }
        $array['greeting'] = 'Welcome!';

        $this->array = $array;
    }

    private function prepareRecursion()
    {
        $this->recursion = $this;
    }
}

$dumper = new LightVarDumper();
$dumper
    ->setMaxStringLength(400)
    ->setMaxLineLength(70);

$dumper->dump(new ExampleData());

/*

Output:

object(ExampleData) #1 (5) {
    public $recursion =>     RECURSIVE object(ExampleData) #1
    protected $emptyArray => array(0) {}
    private $lipsum =>
        string(769)
            › Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nibh au
            › gue, suscipit a, scelerisque sed, lacinia in, mi. Cras vel lorem. Etia
            › m pellentesque aliquet tellus. Phasellus pharetra nulla ac diam. Quisq
            › ue semper justo at risus. Donec venenatis, turpis vel hendrerit interd
            › um, dui ligula ultricies purus, sed posuere libero dui id orci. Nam co
            › ngue, pede vitae dapibus aliquet, elit magna vulpu...
    private $lipsum2 =>
        string(768)
            › Lorem ipsum dolor sit amet,
            › consectetur adipiscing elit.
            › Proin nibh augue, suscipit a,
            › scelerisque sed, lacinia in, mi.
            › Cras vel lorem.
            › Etiam pellentesque aliquet tellus.
            › Phasellus pharetra nulla ac diam.
            › Quisque semper justo at risus.
            › Donec venenatis,
            › turpis vel hendrerit interdum,
            › dui ligula ultricies purus,
            › sed posuere libero dui id orci.
            › Nam congue, pede vitae dapibus aliquet,
            › elit magna vulpu...
    $array =>
        array(11) {
            [0] =>          0
            [01] =>         1
            [012] =>        2
            [0123] =>       3
            [01234] =>      4
            [012345] =>     5
            [0123456] =>    6
            [01234567] =>   7
            [012345678] =>  8
            [0123456789] => 9
            [greeting] =>   “Welcome!”
        }
}

*/
