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

use Awesomite\VarDumper\Helpers\FileNameDecorator;

/**
 * @internal
 */
final class FileLineTest extends BaseTestCase
{
    /**
     * @dataProvider providerDumpers
     *
     * @param VarDumperInterface $dumper
     * @param bool               $displayLines
     */
    public function testFileLine(VarDumperInterface $dumper, $displayLines)
    {
        \ob_start();
        $dumper->dump(1);
        $contents = \ob_get_contents();
        \ob_end_clean();
        list($firstLine) = \explode("\n", $contents);
        $this->assertSame($displayLines, FileNameDecorator::decorateFileName(__FILE__) . ':' . (__LINE__ - 4) . ':' === $firstLine, $contents);
    }

    public function providerDumpers()
    {
        return array(
            array(new LightVarDumper(true), true),
            array(new InternalVarDumper(true), true),
            array(new LightVarDumper(), false),
            array(new InternalVarDumper(), false),
        );
    }

    /**
     * @dataProvider providerNestingLevel
     *
     * @param int $nestingLevel
     */
    public function testNestingLevel($nestingLevel)
    {
        if ($nestingLevel < 0 || $nestingLevel > 2) {
            throw new \InvalidArgumentException('Nesting level must be equal to 0, 1 or 2');
        }
        $dumper = new LightVarDumper(true, $nestingLevel);
        $dump1 = function () use ($dumper) {
            $dumper->dump('foo');
        };
        $dump2 = function () use ($dump1) {
            $dump1();
        };
        $dump3 = function () use ($dump2) {
            $dump2();
        };

        $nestingMapping = array(
            0 => 21,
            1 => 18,
            2 => 15,
        );

        \ob_start();
        $dump3();
        $contents = \ob_get_contents();
        \ob_end_clean();
        list($firstLine) = \explode("\n", $contents);

        $this->assertSame(FileNameDecorator::decorateFileName(__FILE__) . ':' . (__LINE__ - $nestingMapping[$nestingLevel]) . ':', $firstLine, $contents);
    }

    public function providerNestingLevel()
    {
        return array(
            array(0),
            array(1),
            array(2),
        );
    }
}
