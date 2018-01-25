<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $result = [];
        $result['visibilityModifiers'] = $this->getVisibilityModifiers();
        $result['arrayObject'] = $this->getArrayObject();
        $result['extendedArrayObject'] = $this->getExtendedArrayObject();
        $result['extendedArrayObject2'] = $this->getExtendedArrayObject2();
        $result['null'] = [null, "NULL\n"];
        $result['resource'] = [\tmpfile(), "#resource (\#[0-9]+ )?of type stream\n#"];
        $result['closure'] = $this->getClosure();
        $result['debugInfo'] = $this->getDebugInfo();
        $result['debugInfo2'] = $this->getDebugInfo2();
        $result['debugInfo3'] = $this->getDebugInfo3();
        $result['brokenAlign'] = $this->getBrokenAlign();
        $result['1-element_array'] = $this->get1ElementArray();
        $result['1-element_long_array'] = $this->get1ElementLongArray();
        $result['single_line_short_text'] = $this->getSingleLineShortText();
        $result['single_line_long_text'] = $this->getSingleLineLongText();
        $result['short_array'] = $this->getShortArray();
        $result['short_array2'] = $this->getShortArray2();
        $result['short_array3'] = $this->getShortArray3();
        $result['short_array_long_key'] = $this->getShortArrayLongKey();
        $result['single_element'] = $this->getSingleElementDump();
        $result['white_chars'] = $this->getWhiteChars();
        $result['white_chars2'] = $this->getWhiteChars2();
        $result['simple_array_with_white_spaces'] = $this->getSimpleArrayWithWhiteSpaces();
        $result['edge_case_for_simple_array'] = $this->getEdgeCaseForSimpleArray();

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

        $objectDump
            = <<<OBJECT
object(Awesomite\VarDumper\LightVarDumperProviders\TestObject) #{$hasher->getHashId($object)} (5) {
    public static \$static =>             “static value”
    public \$public =>                    “public variable”
    protected \$protected =>              “protected variable”
    protected static \$protectedStatic => “protected static value”
    \$dynamicPublic =>                    “another public variable”
}

OBJECT;

        return [$object, $objectDump];
    }

    private function getArrayObject()
    {
        $hasher = HasherFactory::create();

        $arrayObject = new \ArrayObject();
        $arrayObject['awesomite.varDumper'] = true;

        $arrayObjectDump
            = <<<DUMP
object(ArrayObject) #{$hasher->getHashId($arrayObject)} (3) {
    private \$storage =>       array(1) {[awesomite.varDumper] => true}
    private \$flags =>         0
    private \$iteratorClass => “ArrayIterator”
}

DUMP;

        return [$arrayObject, $arrayObjectDump];
    }

    private function getExtendedArrayObject()
    {
        $hasher = HasherFactory::create();

        $testArrayObject = new TestArrayObject();
        $testArrayObject['awesomite.varDumper'] = true;

        $testArrayObjectDump
            = <<<DUMP
object(Awesomite\VarDumper\LightVarDumperProviders\TestArrayObject) #{$hasher->getHashId($testArrayObject)} (4) {
    private \$privateProperty => “private value”
    private \$storage =>         array(1) {[awesomite.varDumper] => true}
    private \$flags =>           0
    private \$iteratorClass =>   “ArrayIterator”
}

DUMP;

        return [$testArrayObject, $testArrayObjectDump];
    }

    private function getExtendedArrayObject2()
    {
        $hasher = HasherFactory::create();

        $testArrayObject2 = new TestArrayObject();
        $testArrayObject2['privateProperty'] = 'public value';
        $testArrayObject2['secondProperty'] = 'second value';

        $testArrayObjectDump2
            = <<<DUMP
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

        return [$testArrayObject2, $testArrayObjectDump2];
    }

    private function getClosure()
    {
        $closure = function () {
        };

        $dump
            = <<<'DUMP'
object(Closure) #%%digit%% (%%digit%%) {
    $name =>              %%any%%“Awesomite\VarDumper\LightVarDumperProviders\{closure}”
    $filename =>          %%any%%“%%file%%”
    $startLine =>         %%digit%%
    $endLine =>           %%digit%%
    $closureScopeClass => “Awesomite\VarDumper\LightVarDumperProviders\ProviderDump”
}

DUMP;

        $replace = [
            '%%digit%%' => '[0-9]{1,}',
            '%%file%%'  => '.*',
            '%%any%%'   => '.*',
        ];
        $regex = '#^' . \preg_quote($dump, '#') . '$#ms';
        $regex = \str_replace(\array_keys($replace), \array_values($replace), $regex);

        return [$closure, $regex];
    }

    private function getDebugInfo()
    {
        $data = [
            'greeting' => 'hello world',
            'class'    => \get_class(new TestDebugInfo([])),
        ];

        $expected
            = <<<'EXPECTED'
object(Awesomite\VarDumper\LightVarDumperProviders\TestDebugInfo) #%d (2) {[
    [greeting] => “hello world”
    [class] =>    “Awesomite\VarDumper\LightVarDumperProviders\TestDebugInfo”
]}

EXPECTED;

        $hasher = HasherFactory::create();
        $obj = new TestDebugInfo($data);
        $expected = \sprintf($expected, $hasher->getHashId($obj));

        return [$obj, $expected];
    }

    private function getDebugInfo2()
    {
        $data = [1, 2.5, null, INF];

        $expected
            = <<<'EXPECTED'
object(Awesomite\VarDumper\LightVarDumperProviders\TestDebugInfo) #%d (4) {[
    [0] => 1
    [1] => 2.5
    [2] => NULL
    [3] => INF
]}

EXPECTED;

        $hasher = HasherFactory::create();
        $obj = new TestDebugInfo($data);
        $expected = \sprintf($expected, $hasher->getHashId($obj));

        return [$obj, $expected];
    }

    private function getDebugInfo3()
    {
        $data = [];

        $expected
            = <<<'EXPECTED'
object(Awesomite\VarDumper\LightVarDumperProviders\TestDebugInfo) #%d (0) {[]}

EXPECTED;

        $hasher = HasherFactory::create();
        $obj = new TestDebugInfo($data);
        $expected = \sprintf($expected, $hasher->getHashId($obj));

        return [$obj, $expected];
    }


    private function getBrokenAlign()
    {
        $data = [
            'a'        => 'a',
            'ab'       => 'ab',
            'abc'      => 'abc',
            'subarray' => \range('a', 'm'),
            'abcd'     => 'abcd',
        ];

        $expected
            = <<<'EXPECTED'
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


        return [$data, $expected];
    }

    private function get1ElementArray()
    {
        $zeros = \implode('', \array_fill(0, $this->getDefaultLineLength(), '0'));

        $array = [
            'first'  => [null],
            'second' => null,
            'third'  => $zeros,
        ];
        $expected
            = <<<EXPECTED
array(3) {
    [first] =>  array(1) {NULL}
    [second] => NULL
    [third] =>  “{$zeros}”
}

EXPECTED;

        return [$array, $expected];
    }

    private function get1ElementLongArray()
    {
        $lineLength = $this->getDefaultLineLength();
        $textLength = $lineLength + 5;

        $zeros = \implode('', \array_fill(0, $textLength, '0'));

        $array = [
            'first'  => [1],
            'second' => null,
            'third'  => $zeros,
        ];

        $zerosLine = \implode('', \array_fill(0, $lineLength, '0'));
        $expected
            = <<<EXPECTED
array(3) {
    [first] =>  array(1) {1}
    [second] => NULL
    [third] =>
        string({$textLength})
            › {$zerosLine}
            › 00000
}

EXPECTED;

        return [$array, $expected];
    }

    private function getSingleLineShortText()
    {
        $zeros = \implode('', \array_fill(0, $this->getDefaultLineLength(), '0'));

        return [$zeros, "“{$zeros}”\n"];
    }

    private function getSingleLineLongText()
    {
        $lineLength = $this->getDefaultLineLength();
        $textLength = $lineLength + 5;

        $zeros = \implode('', \array_fill(0, $textLength, '0'));
        $zerosLine = \implode('', \array_fill(0, $lineLength, '0'));

        $expected
            = <<<EXPECTED
string({$textLength})
    › {$zerosLine}
    › 00000

EXPECTED;

        return [$zeros, $expected];
    }

    private function getShortArray()
    {
        $data = [1, 2, null, 4.5, INF];
        $dump
            = <<<'DUMP'
array(5) {1, 2, NULL, 4.5, INF}

DUMP;

        return [$data, $dump];
    }

    private function getShortArray2()
    {
        $data = [
            -1                => -1,
            "multi line\nkey" => M_PI,
            null,
            [],
        ];
        $dump
            = <<<'DUMP'
array(4) {[-1] => -1, [multi line↵key] => M_PI, [0] => NULL, [1] => array(0) {}}

DUMP;

        return [$data, $dump];
    }

    private function getShortArray3()
    {
        $data = ['a' => 0, 'b' => 1, 2 => null, 3 => 3];
        $dump
            = <<<'DUMP'
array(4) {[a] => 0, [b] => 1, [2] => NULL, [3] => 3}

DUMP;

        return [$data, $dump];
    }

    private function getShortArrayLongKey()
    {
        $data = [
            'very_very_very_long_key' => 'value',
        ];
        $dump
            = <<<'DUMP'
array(1) {
    [very_very_very_long_key] => “value”
}

DUMP;

        return [$data, $dump];
    }

    private function getSingleElementDump()
    {
        $data = ['Line of file'];
        $dump
            = <<<'DUMP'
array(1) {“Line of file”}

DUMP;

        return [$data, $dump];
    }

    private function getWhiteChars()
    {
        $data = "\t\r\0\x0B";
        $dump
            = <<<'EXPECTED'
“\t\r\0\v”

EXPECTED;

        return [$data, $dump];
    }

    private function getWhiteChars2()
    {
        $data = "\t\r\0\n\n\x0B";
        $dump
            = <<<'EXPECTED'
string(6)
    › \t\r\0↵
    › ↵
    › \v

EXPECTED;

        return [$data, $dump];
    }

    private function getSimpleArrayWithWhiteSpaces()
    {
        $data = ["hello\tworld!"];
        $dump
            = <<<'DUMP'
array(1) {“hello\tworld!”}

DUMP;

        return [$data, $dump];
    }

    private function getEdgeCaseForSimpleArray()
    {
        $qqq = \array_fill(0, LightVarDumper::DEFAULT_MAX_LINE_LENGTH - 1, 'q');
        $qqq = \implode('', $qqq);

        $data = [$qqq . "\t",];
        $dump
            = <<<'DUMP'
array(1) {
    [0] =>
        string(%length%)
            › %qqq%
            › \t
}

DUMP;
        $replace = [
            '%length%' => (string)LightVarDumper::DEFAULT_MAX_LINE_LENGTH,
            '%qqq%'    => $qqq,
        ];
        $dump = \str_replace(
            \array_keys($replace),
            \array_values($replace),
            $dump
        );

        return [$data, $dump];
    }

    private function getDefaultLineLength()
    {
        return LightVarDumper::DEFAULT_MAX_LINE_LENGTH;
    }
}
