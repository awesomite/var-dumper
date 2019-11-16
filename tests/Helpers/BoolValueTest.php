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
final class BoolValueTest extends BaseTestCase
{
    /**
     * @dataProvider providerAll
     *
     * @param bool $value
     */
    public function testAll($value)
    {
        $boolObject = new BoolValue($value);
        $this->assertSame($value, $boolObject->getValue());

        foreach (array(true, false) as $currentBool) {
            $boolObject->setValue($currentBool);
            $this->assertSame($currentBool, $boolObject->getValue());
        }
    }

    public function providerAll()
    {
        return array(
            array(true),
            array(false),
        );
    }
}
