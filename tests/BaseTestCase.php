<?php

namespace Awesomite\VarDumper;

/**
 * @internal
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->expectOutputString('');
    }
}
