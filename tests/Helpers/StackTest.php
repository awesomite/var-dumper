<?php

namespace Awesomite\VarDumper\Helpers;

use Awesomite\VarDumper\BaseTestCase;

/**
 * @internal
 */
class StackTest extends BaseTestCase
{
    /**
     * @dataProvider providerIn
     *
     * @param Stack $stack
     * @param       $element
     * @param       $expected
     */
    public function testIn(Stack $stack, $element, $expected)
    {
        $this->assertInternalType('bool', $expected);
        $this->assertSame($expected, $stack->in($element));
    }

    public function providerIn()
    {
        $stack = new Stack();
        $a = '1';
        $b = 2;
        $stack->push($a);
        $stack->push($b);

        return array(
            array($stack, 1, false),
            array($stack, '1', true),
            array($stack, 2, true),
            array($stack, '2', false),
        );
    }
}

