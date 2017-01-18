<?php

namespace Awesomite\VarDumper;

class DumperTest extends BaseTestCase
{
    /**
     * @dataProvider  providerDump
     *
     * @param $input
     * @param $expectedOutput
     */
    public function testDump($input, $expectedOutput)
    {
        ob_start();
        Dumper::dump($input);
        $contents = ob_get_contents();
        ob_end_clean();

        $exploded = explode("\n", $contents);
        array_shift($exploded);
        $result = implode("\n", $exploded);

        $this->assertSame($result, $expectedOutput);
    }

    public function providerDump()
    {
        return array(
            array(1, "int(1)\n"),
        );
    }
}