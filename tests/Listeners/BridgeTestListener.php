<?php

if (interface_exists('PHPUnit_Framework_TestListener')) {
    class_alias(
        'Awesomite\VarDumper\Listeners\BridgeTestListener4x',
        'Awesomite\VarDumper\Listeners\BridgeTestListener'
    );
} else {
    class_alias(
        'Awesomite\VarDumper\Listeners\BridgeTestListener6x',
        'Awesomite\VarDumper\Listeners\BridgeTestListener'
    );
}
