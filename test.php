<?php

require __DIR__ . '/vendor/autoload.php';

set_error_handler(function () {
    echo "\n\n";
    var_dump(func_get_args());
    debug_print_backtrace();
    echo "\n\n";
});
