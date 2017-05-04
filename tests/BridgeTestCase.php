<?php

if (class_exists('PHPUnit_Framework_TestCase')) {
    class_alias('PHPUnit_Framework_TestCase', 'Awesomite\VarDumper\BridgeTestCase');
} else {
    class_alias('PHPUnit\Framework\TestCase', 'Awesomite\VarDumper\BridgeTestCase');
}
