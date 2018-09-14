<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper;

/**
 * @internal
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->expectOutputString('');
    }
}
