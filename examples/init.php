<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$paths = [
    \implode(DIRECTORY_SEPARATOR, ['..', 'vendor', 'autoload.php']),
    \implode(DIRECTORY_SEPARATOR, ['..', '..', '..', 'autoload.php']),
];

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
