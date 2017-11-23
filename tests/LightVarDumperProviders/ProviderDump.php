<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

use Awesomite\VarDumper\LightVarDumper;
use Awesomite\VarDumper\Objects\HasherFactory;

/**
 * @internal
 */
class ProviderDump implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = array();
        $result['visibilityModifiers'] = $this->getVisibilityModifiers();
        if (!\defined('HHVM_VERSION')) {
            $result['arrayObject'] = $this->getArrayObject();
            $result['extendedArrayObject'] = $this->getExtendedArrayObject();
            $result['extendedArrayObject2'] = $this->getExtendedArrayObject2();
        }
        $result['null'] = array(null, "NULL\n");
        $result['resource'] = array(\tmpfile(), "#resource (\#[0-9]+ )?of type stream\n#");
        if (!\defined('HHVM_VERSION') && \version_compare(PHP_VERSION, '5.4') >= 0) {
            $result['closure'] = $this->getClosure();
        }
        $result['debugInfo'] = $this->getDebugInfo();
        $result['brokenAlign'] = $this->getBrokenAlign();
        if (\version_compare(PHP_VERSION, '5.6') < 0) {
            $result['invalidDebugInfo'] = $this->getInvalidDebugInfo();
        }
        $result['1-element_array'] = $this->get1ElementArray();
        $result['1-element_long_array'] = $this->get1ElementLongArray();
        $result['single_line_short_text'] = $this->getSingleLineShortText();
        $result['single_line_long_text'] = $this->getSingleLineLongText();

        return new \ArrayIterator($result);
    }

    private function getVisibilityModifiers()
    {
        $hasher = HasherFactory::create();

        $object = new TestObject();
        $object->setPrivate('private variable');
        $object->setProtected('protected variable');
        $object->public = 'public variable';
        $object->dynamicPublic = 'another public variable';

        $objectDump = <<<OBJECT
object(Awesomite\VarDumper\LightVarDumperProviders\TestObject) #{$hasher->getHashId($object)} (5) {
    public static \$static =>             “static value”
    public \$public =>                    “public variable”
    protected \$protected =>              “protected variable”
    protected static \$protectedStatic => “protected static value”
    \$dynamicPublic =>                    “another public variable”
}

OBJECT;

        return array($object, $objectDump);
    }

    private function getArrayObject()
    {
        $hasher = HasherFactory::create();

        $arrayObject = new \ArrayObject();
        $arrayObject['awesomite.varDumper'] = true;

        $arrayObjectDump = <<<DUMP
object(ArrayObject) #{$hasher->getHashId($arrayObject)} (3) {
    private \$storage =>
        array(1) {
            [awesomite.varDumper] => true
        }
    private \$flags =>         0
    private \$iteratorClass => “ArrayIterator”
}

DUMP;

        return array($arrayObject, $arrayObjectDump);
    }

    private function getExtendedArrayObject()
    {
        $hasher = HasherFactory::create();

        $testArrayObject = new TestArrayObject();
        $testArrayObject['awesomite.varDumper'] = true;

        $testArrayObjectDump = <<<DUMP
object(Awesomite\VarDumper\LightVarDumperProviders\TestArrayObject) #{$hasher->getHashId($testArrayObject)} (4) {
    private \$privateProperty => “private value”
    private \$storage =>
        array(1) {
            [awesomite.varDumper] => true
        }
    private \$flags =>         0
    private \$iteratorClass => “ArrayIterator”
}

DUMP;

        return array($testArrayObject, $testArrayObjectDump);
    }

    private function getExtendedArrayObject2()
    {
        $hasher = HasherFactory::create();

        $testArrayObject2 = new TestArrayObject();
        $testArrayObject2['privateProperty'] = 'public value';
        $testArrayObject2['secondProperty'] = 'second value';

        $testArrayObjectDump2 = <<<DUMP
object(Awesomite\VarDumper\LightVarDumperProviders\TestArrayObject) #{$hasher->getHashId($testArrayObject2)} (4) {
    private \$privateProperty => “private value”
    private \$storage =>
        array(2) {
            [privateProperty] => “public value”
            [secondProperty] =>  “second value”
        }
    private \$flags =>         0
    private \$iteratorClass => “ArrayIterator”
}

DUMP;

        return array($testArrayObject2, $testArrayObjectDump2);
    }

    private function getClosure()
    {
        $closure = function () {
        };

        $dump =<<<'DUMP'
object(Closure) #%%digit%% (%%digit%%) {
    $name =>              %%any%%“Awesomite\VarDumper\LightVarDumperProviders\{closure}”
    $filename =>          %%any%%“%%file%%”
    $startLine =>         %%digit%%
    $endLine =>           %%digit%%
    $closureScopeClass => “Awesomite\VarDumper\LightVarDumperProviders\ProviderDump”
}

DUMP;

        $replace = array(
            '%%digit%%' => '[0-9]{1,}',
            '%%file%%' => '.*',
            '%%any%%' => '.*',
        );
        $regex = '#^' . \preg_quote($dump, '#') . '$#ms';
        $regex = \str_replace(\array_keys($replace), \array_values($replace), $regex);

        return array($closure, $regex);
    }

    private function getDebugInfo()
    {
        $data = array(
            'greeting' => 'hello world',
            'class' => \get_class(new TestDebugInfo(array()))
        );

        $expected = <<<'EXPECTED'
object(Awesomite\VarDumper\LightVarDumperProviders\TestDebugInfo) #%s (1) {
    public $__debugInfo() =>
        array(2) {
            [greeting] => “hello world”
            [class] =>    “Awesomite\VarDumper\LightVarDumperProviders\TestDebugInfo”
        }
}

EXPECTED;

        $hasher = HasherFactory::create();
        $obj = new TestDebugInfo($data);
        $expected = \sprintf($expected, $hasher->getHashId($obj));

        return array($obj, $expected);
    }

    private function getBrokenAlign()
    {
        $data = array(
            'a' => 'a',
            'ab' => 'ab',
            'abc' => 'abc',
            'subarray' => \range('a', 'm'),
            'abcd' => 'abcd',
        );

        $expected = <<<'EXPECTED'
array(5) {
    [a] =>   “a”
    [ab] =>  “ab”
    [abc] => “abc”
    [subarray] =>
        array(13) {
            [0] =>  “a”
            [1] =>  “b”
            [2] =>  “c”
            [3] =>  “d”
            [4] =>  “e”
            [5] =>  “f”
            [6] =>  “g”
            [7] =>  “h”
            [8] =>  “i”
            [9] =>  “j”
            [10] => “k”
            [11] => “l”
            [12] => “m”
        }
    [abcd] => “abcd”
}

EXPECTED;


        return array($data, $expected);
    }

    private function getInvalidDebugInfo()
    {
        $obj = new TestInvalidDebugInfo();
        $hasher = HasherFactory::create();

        $expected = \sprintf("object(%s) #%d (0) {}\n", \get_class($obj), $hasher->getHashId($obj));

        return array($obj, $expected);
    }

    private function get1ElementArray()
    {
        $zeros = \implode('', \array_fill(0, $this->getDefaultLineLength(), '0'));

        $array = array(
            'first' => array(null),
            'second' => null,
            'third' => $zeros,
        );
        $expected = <<<EXPECTED
array(3) {
    [first] =>  array(1) {NULL}
    [second] => NULL
    [third] =>  “{$zeros}”
}

EXPECTED;
        return array($array, $expected);
    }

    private function get1ElementLongArray()
    {
        $lineLength = $this->getDefaultLineLength();
        $textLength = $lineLength + 5;

        $zeros = \implode('', \array_fill(0, $textLength, '0'));

        $array = array(
            'first' => array(1),
            'second' => null,
            'third' => $zeros,
        );

        $zerosLine = \implode('', \array_fill(0, $lineLength, '0'));
        $expected = <<<EXPECTED
array(3) {
    [first] =>  array(1) {1}
    [second] => NULL
    [third] =>
        string({$textLength})
            › {$zerosLine}
            › 00000
}

EXPECTED;
        return array($array, $expected);
    }

    private function getSingleLineShortText()
    {
        $zeros = \implode('', \array_fill(0, $this->getDefaultLineLength(), '0'));

        return array($zeros, "“{$zeros}”\n");
    }

    private function getSingleLineLongText()
    {
        $lineLength = $this->getDefaultLineLength();
        $textLength = $lineLength + 5;

        $zeros = \implode('', \array_fill(0, $textLength, '0'));
        $zerosLine = \implode('', \array_fill(0, $lineLength, '0'));

        $expected = <<<EXPECTED
string({$textLength})
    › {$zerosLine}
    › 00000

EXPECTED;

        return array($zeros, $expected);
    }

    private function getDefaultLineLength()
    {
        return LightVarDumper::DEFAULT_MAX_LINE_LENGTH;
    }
}
