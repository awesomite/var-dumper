<?php

namespace Awesomite\VarDumper;

/**
 * @internal
 */
class LightVarDumperTest extends BaseTestCase
{
    /**
     * @dataProvider providerDump
     *
     * @param mixed $var
     * @param string $expectedDump
     */
    public function testDump($var, $expectedDump)
    {
        $dumper = new LightVarDumper();
        $this->assertSame($expectedDump, $dumper->getDump($var));
    }

    public function providerDump()
    {
        $object = new TestObject();
        $object->setPrivate('private variable');
        $object->setProtected('protected variable');
        $object->public = 'public variable';
        $object->dynamicPublic = 'another public variable';

        $objectDump = <<<'OBJECT'
object(Awesomite\VarDumper\TestObject) (6) {
  public static $static => 
  string(12) 'static value'
  public $public => 
  string(15) 'public variable'
  protected $protected => 
  string(18) 'protected variable'
  protected static $protectedStatic @Awesomite\VarDumper\TestParent => 
  string(22) 'protected static value'
  $dynamicPublic => 
  string(23) 'another public variable'
  private $private @Awesomite\VarDumper\TestParent => 
  string(16) 'private variable'
}

OBJECT;

        $arrayObject = new \ArrayObject();
        $arrayObject['awesomite.varDumper'] = true;
        $arrayObjectDump = <<<'DUMP'
object(ArrayObject) (3) {
  private $storage => 
  array(1) {
    [awesomite.varDumper] => 
    bool(true)
  }
  private $flags => 
  int(0)
  private $iteratorClass => 
  string(13) 'ArrayIterator'
}

DUMP;

        $testArrayObject = new TestArrayObject();
        $testArrayObject['awesomite.varDumper'] = true;
        $testArrayObjectDump = <<<'DUMP'
object(Awesomite\VarDumper\TestArrayObject) (4) {
  private $privateProperty => 
  string(13) 'private value'
  private $storage @ArrayObject => 
  array(1) {
    [awesomite.varDumper] => 
    bool(true)
  }
  private $flags @ArrayObject => 
  int(0)
  private $iteratorClass @ArrayObject => 
  string(13) 'ArrayIterator'
}

DUMP;

        $testArrayObject2 = new TestArrayObject();
        $testArrayObject2['privateProperty'] = 'public value';
        $testArrayObject2['secondProperty'] = 'second value';
        $testArrayObjectDump2 = <<<'DUMP'
object(Awesomite\VarDumper\TestArrayObject) (4) {
  private $privateProperty => 
  string(13) 'private value'
  private $storage @ArrayObject => 
  array(2) {
    [privateProperty] => 
    string(12) 'public value'
    [secondProperty] => 
    string(12) 'second value'
  }
  private $flags @ArrayObject => 
  int(0)
  private $iteratorClass @ArrayObject => 
  string(13) 'ArrayIterator'
}

DUMP;

        $result = array(
            array(false, "bool(false)\n"),
            array(150, "int(150)\n"),
            array(new \stdClass(), "object(stdClass) (0) {\n}\n"),
            array(null, "NULL\n"),
            array($object, $objectDump),
            array(tmpfile(), "resource of type stream\n"),
        );

        if (!defined('HHVM_VERSION')) {
            $result = array_merge($result, array(
                array($arrayObject, $arrayObjectDump),
                array($testArrayObject, $testArrayObjectDump),
                array($testArrayObject2, $testArrayObjectDump2),
            ));
        }

        return $result;
    }

    /**
     * @dataProvider providerMaxDepth
     *
     * @param int $limit
     * @param $var
     * @param string $dump
     */
    public function testMaxDepth($limit, $var, $dump)
    {
        $dumper = new LightVarDumper();
        $dumper2 = $dumper->setMaxDepth($limit);
        $this->assertSame($dumper2, $dumper);
        $this->assertSame($dump, $dumper->getDump($var));
    }

    public function providerMaxDepth()
    {
        $obj = new \stdClass();
        $obj->testInt = 5;
        $obj->foo = new \stdClass();
        $obj->foo->bar = new \stdClass();

        $dump1 = <<<'DUMP'
object(stdClass) (2) {
  $testInt => 
  int(5)
  $foo => 
  object(stdClass) (1) {
    ...
  }
}

DUMP;

        $dump2 = <<<'DUMP'
object(stdClass) (2) {
  $testInt => 
  int(5)
  $foo => 
  object(stdClass) (1) {
    $bar => 
    object(stdClass) (0) {
    }
  }
}

DUMP;

        $array = array(
            'foo' => array(
                'bar',
            ),
        );

        $dumpArray = <<<'DUMP'
array(1) {
  [foo] => 
  array(1) {
    ...
  }
}

DUMP;


        return array(
            array(1, $obj, $dump1),
            array(2, $obj, $dump2),
            array(3, $obj, $dump2),
            array(1, $array, $dumpArray),
        );
    }

    /**
     * @dataProvider providerMaxStringLength
     *
     * @param int $limit
     * @param string $string
     * @param string $dump
     */
    public function testMaxStringLength($limit, $string, $dump)
    {
        $dumper = new LightVarDumper();
        $dumper2 = $dumper->setMaxStringLength($limit);
        $this->assertSame($dumper2, $dumper);
        $this->assertSame($dump, $dumper->getDump($string));
    }

    public function providerMaxStringLength()
    {
        return array(
            array(5, 'Hello world!', "string(12) 'Hello'...\n"),
            array(2, 'Hello world!', "string(12) 'He'...\n"),
        );
    }

    /**
     * @dataProvider providerMaxChildren
     *
     * @param int $limit
     * @param $iterable
     * @param string $dump
     */
    public function testMaxChildren($limit, $iterable, $dump)
    {
        $dumper = new LightVarDumper();
        $dumper2 = $dumper->setMaxChildren($limit);
        $this->assertSame($dumper2, $dumper);
        $this->assertSame($dump, $dumper->getDump($iterable));
    }

    public function providerMaxChildren()
    {
        $array = array(1, 2, 3);

        $arrayDump1 = <<<'DUMP'
array(3) {
  [0] => 
  int(1)
  (...)
}

DUMP;

        $arrayDump2 = <<<'DUMP'
array(3) {
  [0] => 
  int(1)
  [1] => 
  int(2)
  [2] => 
  int(3)
}

DUMP;

        $object = new \stdClass();
        $object->foo = 'foo';
        $object->bar = 'bar';
        $object->foobar = 'foobar';

        $objectDump1 = <<<'DUMP'
object(stdClass) (3) {
  $foo => 
  string(3) 'foo'
  (...)
}

DUMP;

        $objectDump2 = <<<'DUMP'
object(stdClass) (3) {
  $foo => 
  string(3) 'foo'
  $bar => 
  string(3) 'bar'
  $foobar => 
  string(6) 'foobar'
}

DUMP;

        return array(
            array(1, $array, $arrayDump1),
            array(3, $array, $arrayDump2),
            array(1, $object, $objectDump1),
            array(3, $object, $objectDump2),
        );
    }

    /**
     * @dataProvider providerRecursive
     *
     * @param $var
     * @param string $dump
     */
    public function testRecursive($var, $dump)
    {
        $dumper = new LightVarDumper();
        $this->assertSame($dump, $dumper->getDump($var));
    }

    public function providerRecursive()
    {
        $recursiveObj = new \stdClass();
        $recursiveObj->self = $recursiveObj;
        $objectDump = <<<'DUMP'
object(stdClass) (1) {
  $self => 
  RECURSIVE object(stdClass)
}

DUMP;

        $recursiveArr = array();
        $recursiveArr[] = &$recursiveArr;
        $arrayDump = <<<'DUMP'
array(1) {
  [0] => 
  RECURSIVE array(1)
}

DUMP;

        $result = array(
            array($recursiveObj, $objectDump),
        );
        if (version_compare(PHP_VERSION, '5.4.5' >= 0)) {
            $result[] = array($recursiveArr, $arrayDump);
        }

        return $result;
    }
}