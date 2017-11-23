<?php

$paths = array(
    \implode(DIRECTORY_SEPARATOR, array('..', 'vendor', 'autoload.php')),
    \implode(DIRECTORY_SEPARATOR, array('..', '..', '..', 'autoload.php')),
);

$included = false;
foreach ($paths as $path) {
    if (\is_file(__DIR__ . DIRECTORY_SEPARATOR . $path)) {
        require_once __DIR__ . DIRECTORY_SEPARATOR . $path;
        $included = true;
        break;
    }
}

if (!$included) {
    echo "Install composer (@see https://getcomposer.org/), then execute 'composer install'\n";
    exit(1);
}
