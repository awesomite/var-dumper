<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper;

require \implode(\DIRECTORY_SEPARATOR, array(__DIR__, '..', 'vendor', 'autoload.php'));

\set_error_handler(
    function ($no, $message, $file, $line) {
        throw new \ErrorException($message, 0, $no, $file, $no);
    },
    \E_ALL | \E_STRICT
);

\set_exception_handler(
    function ($exception) {
        /** @var \Exception $exception */
        echo '[', \get_class($exception), '] ', $exception->getMessage(), "\n";
        echo $exception->getTraceAsString(), "\n";
        exit(1);
    }
);

$dumper = new SymfonyVarDumper();
$dumped = $dumper->dumpAsString('Hello world!');
if (!\is_string($dumped) || '' === $dumped) {
    throw new \UnexpectedValueException('Method dumpAsString must return not empty string.');
}
$dumper->dump('Tests passed.');
