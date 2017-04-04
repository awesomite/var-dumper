<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class ProviderDump implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = array();
        $result['visibilityModifiers'] = $this->getVisibilityModifiers();
        $result['arrayObject'] = $this->getArrayObject();
        $result['extendedArrayObject'] = $this->getExtendedArrayObject();
        $result['extendedArrayObject2'] = $this->getExtendedArrayObject2();
        $result['null'] = array(null, "NULL\n");
        $result['resource'] = array(tmpfile(), "resource of type stream\n");

        return new \ArrayIterator($result);
    }

    private function getVisibilityModifiers()
    {
        $object = new TestObject();
        $object->setPrivate('private variable');
        $object->setProtected('protected variable');
        $object->public = 'public variable';
        $object->dynamicPublic = 'another public variable';

        $objectDump = <<<'OBJECT'
object(Awesomite\VarDumper\LightVarDumperProviders\TestObject) (6) {
  public static $static =>
  string(12) 'static value'
  public $public =>
  string(15) 'public variable'
  protected $protected =>
  string(18) 'protected variable'
  protected static $protectedStatic @Awesomite\VarDumper\LightVarDumperProviders\TestParent =>
  string(22) 'protected static value'
  $dynamicPublic =>
  string(23) 'another public variable'
  private $private @Awesomite\VarDumper\LightVarDumperProviders\TestParent =>
  string(16) 'private variable'
}

OBJECT;

        return array($object, $objectDump);
    }

    private function getArrayObject()
    {
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

        return array($arrayObject, $arrayObjectDump);
    }

    private function getExtendedArrayObject()
    {
        $testArrayObject = new TestArrayObject();
        $testArrayObject['awesomite.varDumper'] = true;

        $testArrayObjectDump = <<<'DUMP'
object(Awesomite\VarDumper\LightVarDumperProviders\TestArrayObject) (4) {
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

        return array($testArrayObject, $testArrayObjectDump);
    }

    private function getExtendedArrayObject2()
    {
        $testArrayObject2 = new TestArrayObject();
        $testArrayObject2['privateProperty'] = 'public value';
        $testArrayObject2['secondProperty'] = 'second value';

        $testArrayObjectDump2 = <<<'DUMP'
object(Awesomite\VarDumper\LightVarDumperProviders\TestArrayObject) (4) {
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

        return array($testArrayObject2, $testArrayObjectDump2);
    }
}
