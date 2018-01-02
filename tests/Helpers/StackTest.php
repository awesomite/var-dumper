<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
