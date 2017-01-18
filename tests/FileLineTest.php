<?php

namespace Awesomite\VarDumper;

/**
 * @internal
 */
class FileLineTest extends BaseTestCase
{
    /**
     * @dataProvider providerDumpers
     *
     * @param VarDumperInterface $dumper
     * @param bool $displayLines
     */
    public function testFileLine(VarDumperInterface $dumper, $displayLines)
    {
        ob_start();
        $dumper->dump(1);
        $contents = ob_get_contents();
        ob_end_clean();
        list($firstLine) = explode("\n", $contents);
        $this->assertSame($displayLines, __FILE__ . ':' . (__LINE__ - 4) . ':' === $firstLine, $contents);
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
}