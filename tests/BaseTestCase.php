<?php

namespace Awesomite\VarDumper;

/**
 * @internal
 */
class BaseTestCase extends BridgeTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->expectOutputString('');
    }
}
