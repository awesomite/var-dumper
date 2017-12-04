#!/usr/bin/env php
<?php

require implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'vendor', 'autoload.php'));

$reflection = new ReflectionClass('PHPUnit_Util_Getopt');
$file = $reflection->getFileName();
$contents = file_get_contents($file);

$search = <<<'CODE'
        while (list($i, $arg) = each($args)) {
CODE;

$replace = <<<'CODE'
        while (false !== $arg = current($args)) {
            $i = key($args);
            next($args);
CODE;

$contents = str_replace($search, $replace, $contents);
file_put_contents($file, $contents);
