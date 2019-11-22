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

\assert_options(\ASSERT_WARNING, 0);
\assert_options(
    \ASSERT_CALLBACK,
    function ($file, $line, $_, $message) {
        throw new \RuntimeException(\sprintf('%s %s:%d', $message, $file, $line));
    }
);

$dumper = new SymfonyVarDumper();
$dumped = $dumper->dumpAsString('Hello world!');
\assert(
    \is_string($dumped) && '' !== $dumped,
    'Method dumpAsString must return not empty string.'
);
$dumper->dump('Tests passed.');
