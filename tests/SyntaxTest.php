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

/**
 * @internal
 */
final class SyntaxTest extends BaseTestCase
{
    public static function requireWholeSrc()
    {
        $path = \realpath(\implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'src')));
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);
        $regex = new \RegexIterator($iterator, '/^.+\.php$/', \RecursiveRegexIterator::GET_MATCH);
        $counter = 0;
        foreach ($regex as $file) {
            $counter++;
            require_once $file[0];
        }

        return array($path, $counter);
    }

    /**
     * @runInSeparateProcess
     */
    public function testSyntax()
    {
        if (TestEnv::isSpeedTest()) {
            $this->assertTrue(true);

            return;
        }
        list($path, $counter) = static::requireWholeSrc();
        $this->assertInternalType('string', $path);
        $this->assertGreaterThan(0, $counter);
    }
}
