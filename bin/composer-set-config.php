#!/usr/bin/env php
<?php

/**
 * @param $value
 * @param ...$keys
 */
$handle = function ($value, $_) {
    $jsonPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'composer.json';
    $json = \json_decode(\file_get_contents($jsonPath), true);
    if (!isset($json['config'])) {
        $json['config'] = array();
    }

    $config = &$json['config'];

    foreach (\array_slice(\func_get_args(), 2, -1) as $currentKey) {
        if (!isset($config[$currentKey])) {
            $config[$currentKey] = array();
        }
        $config = &$config[$currentKey];
    }

    $config[\array_slice(\func_get_args(), -1, 1)[0]] = $value;

    $options = JSON_UNESCAPED_UNICODE;
    if (\defined('JSON_PRETTY_PRINT')) {
        $options |= \constant('JSON_PRETTY_PRINT');
    }
    \file_put_contents($jsonPath, \json_encode($json, $options));
};

global $argv;
\call_user_func_array($handle, \array_slice($argv, 1));
