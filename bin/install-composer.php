#!/usr/bin/env php
<?php

$destinationFile = 'composer-setup.php';
\copy('https://getcomposer.org/installer', $destinationFile);
$checkSum = \trim(\file_get_contents('https://composer.github.io/installer.sig'));
if (\hash_file('SHA384', 'composer-setup.php') === $checkSum) {
    echo 'Installer verified' . PHP_EOL;
    require_once $destinationFile;
    return;
}

throw new \RuntimeException('Installer corrupt');
