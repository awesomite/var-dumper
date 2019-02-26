<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Helpers;

use Awesomite\VarDumper\BaseTestCase;

/**
 * @internal
 */
final class FileNameDecoratorTest extends BaseTestCase
{
    /**
     * @dataProvider providerDecorateFileName
     *
     * @param string   $input
     * @param string   $output
     * @param null|int $maxDepth
     */
    public function testDecorateFileName($input, $output, $maxDepth = null)
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            $this->markTestSkipped('Feature does not work on Windows');
        }
        $this->assertSame($output, FileNameDecorator::decorateFileName($input, $maxDepth));
    }

    public function providerDecorateFileName()
    {
        return array(
            array('/', '/', 0),
            array('/', '/', 3),
            array('/foo/bar/foobar', '/foo/bar/foobar', 4),
            array('/foo/bar/foobar', '/foo/bar/foobar', 3),
            array('/foo/bar/foobar', '(...)/bar/foobar', 2),
            array('/foo/bar/foobar', '(...)/foobar', 1),
            array('/foo/bar/foobar', '/foo/bar/foobar', 3),
            array('/foo/bar/foobar/directory', '(...)/bar/foobar/directory', 3),
            array('relative_path', 'relative_path', null),
            array('relative_path', 'relative_path', 1),
            array('relative_path', 'relative_path', 3),
        );
    }
}
