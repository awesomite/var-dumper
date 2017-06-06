<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

use Awesomite\VarDumper\Objects\Hasher;

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
        $result['closure'] = $this->getClosure();

        return new \ArrayIterator($result);
    }

    private function getVisibilityModifiers()
    {
        $hasher = new Hasher();

        $object = new TestObject();
        $object->setPrivate('private variable');
        $object->setProtected('protected variable');
        $object->public = 'public variable';
        $object->dynamicPublic = 'another public variable';

        $objectDump = <<<OBJECT
object(Awesomite\VarDumper\LightVarDumperProviders\TestObject) #{$hasher->getHashId($object)} (6) {
  public static \$static =>
  string(12) 'static value'
  public \$public =>
  string(15) 'public variable'
  protected \$protected =>
  string(18) 'protected variable'
  protected static \$protectedStatic @Awesomite\VarDumper\LightVarDumperProviders\TestParent =>
  string(22) 'protected static value'
  \$dynamicPublic =>
  string(23) 'another public variable'
  private \$private @Awesomite\VarDumper\LightVarDumperProviders\TestParent =>
  string(16) 'private variable'
}

OBJECT;

        return array($object, $objectDump);
    }

    private function getArrayObject()
    {
        $hasher = new Hasher();

        $arrayObject = new \ArrayObject();
        $arrayObject['awesomite.varDumper'] = true;

        $arrayObjectDump = <<<DUMP
object(ArrayObject) #{$hasher->getHashId($arrayObject)} (3) {
  private \$storage =>
  array(1) {
    [awesomite.varDumper] =>
    bool(true)
  }
  private \$flags =>
  int(0)
  private \$iteratorClass =>
  string(13) 'ArrayIterator'
}

DUMP;

        return array($arrayObject, $arrayObjectDump);
    }

    private function getExtendedArrayObject()
    {
        $hasher = new Hasher();

        $testArrayObject = new TestArrayObject();
        $testArrayObject['awesomite.varDumper'] = true;

        $testArrayObjectDump = <<<DUMP
object(Awesomite\VarDumper\LightVarDumperProviders\TestArrayObject) #{$hasher->getHashId($testArrayObject)} (4) {
  private \$privateProperty =>
  string(13) 'private value'
  private \$storage @ArrayObject =>
  array(1) {
    [awesomite.varDumper] =>
    bool(true)
  }
  private \$flags @ArrayObject =>
  int(0)
  private \$iteratorClass @ArrayObject =>
  string(13) 'ArrayIterator'
}

DUMP;

        return array($testArrayObject, $testArrayObjectDump);
    }

    private function getExtendedArrayObject2()
    {
        $hasher = new Hasher();

        $testArrayObject2 = new TestArrayObject();
        $testArrayObject2['privateProperty'] = 'public value';
        $testArrayObject2['secondProperty'] = 'second value';

        $testArrayObjectDump2 = <<<DUMP
object(Awesomite\VarDumper\LightVarDumperProviders\TestArrayObject) #{$hasher->getHashId($testArrayObject2)} (4) {
  private \$privateProperty =>
  string(13) 'private value'
  private \$storage @ArrayObject =>
  array(2) {
    [privateProperty] =>
    string(12) 'public value'
    [secondProperty] =>
    string(12) 'second value'
  }
  private \$flags @ArrayObject =>
  int(0)
  private \$iteratorClass @ArrayObject =>
  string(13) 'ArrayIterator'
}

DUMP;

        return array($testArrayObject2, $testArrayObjectDump2);
    }

    private function getClosure()
    {
        $closure = function () {};
        $dump =<<<'DUMP'
object(Closure) #10 (5) {
  $name =>
  string(53) 'Awesomite\\VarDumper\\LightVarDumperProviders\\{closure}'
  $filename =>
  string(%%digit%%) '%%file%%'
  $startLine =>
  int(%%digit%%)
  $endLine =>
  int(%%digit%%)
  $closureScopeClass =>
  string(56) 'Awesomite\\VarDumper\\LightVarDumperProviders\\ProviderDump'
}

DUMP;
        $replace = array(
            '%%digit%%' => '[0-9]{1,}',
            '%%file%%' => '.*',
        );
        $regex = '#^' . preg_quote($dump, '#') . '$#ms';
        $regex = str_replace(array_keys($replace), array_values($replace), $regex);

        return array($closure, $regex);
    }
}
