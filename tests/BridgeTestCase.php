<?php

namespace Awesomite\VarDumper;

use PHPUnit\Framework\TestCase;

if (class_exists('PHPUnit_Framework_TestCase')) {
    /**
     * @internal
     */
    abstract class BridgeTestCase extends \PHPUnit_Framework_TestCase
    {
    }
} elseif (class_exists('PHPUnit\Framework\TestCase')) {
    /**
     * @internal
     */
    abstract class BridgeTestCase extends TestCase
    {
    }
}
