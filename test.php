<?php

require __DIR__ . '/vendor/autoload.php';

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo "\n\n";
    var_dump($errfile, $errline);
    debug_print_backtrace();
    echo "\n\n";
});
