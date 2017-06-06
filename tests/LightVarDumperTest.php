<?php

namespace Awesomite\VarDumper;

use Awesomite\VarDumper\LightVarDumperProviders\ProviderDump;
use Awesomite\VarDumper\LightVarDumperProviders\ProviderMaxChildren;
use Awesomite\VarDumper\LightVarDumperProviders\ProviderMaxDepth;
use Awesomite\VarDumper\LightVarDumperProviders\ProviderMaxStringLength;
use Awesomite\VarDumper\LightVarDumperProviders\ProviderRecursive;

/**
 * @internal
 */
class LightVarDumperTest extends BaseTestCase
{
    /**
     * @dataProvider providerDump
     *
     * @param mixed $var
     * @param string $expectedDump
     */
    public function testDump($var, $expectedDump)
    {
        $dumper = new LightVarDumper();
        if ($expectedDump[0] === '#') {
            $this->assertRegExp($expectedDump, $dumper->getDump($var));
        } else {
            $this->assertSame($expectedDump, $dumper->getDump($var));
        }
    }

    public function providerDump()
    {
        return iterator_to_array(new ProviderDump());
    }

    /**
     * @dataProvider providerMaxDepth
     *
     * @param int $limit
     * @param $var
     * @param string $dump
     */
    public function testMaxDepth($limit, $var, $dump)
    {
        $dumper = new LightVarDumper();
        $dumper2 = $dumper->setMaxDepth($limit);
        $this->assertSame($dumper2, $dumper);
        $this->assertSame($dump, $dumper->getDump($var));
    }

    public function providerMaxDepth()
    {
        return iterator_to_array(new ProviderMaxDepth());
    }

    /**
     * @dataProvider providerMaxStringLength
     *
     * @param int $limit
     * @param string $string
     * @param string $dump
     */
    public function testMaxStringLength($limit, $string, $dump)
    {
        $dumper = new LightVarDumper();
        $dumper2 = $dumper->setMaxStringLength($limit);
        $this->assertSame($dumper2, $dumper);
        $this->assertSame($dump, $dumper->getDump($string));
    }

    public function providerMaxStringLength()
    {
        return iterator_to_array(new ProviderMaxStringLength());
    }

    /**
     * @dataProvider providerMaxChildren
     *
     * @param int $limit
     * @param $iterable
     * @param string $dump
     */
    public function testMaxChildren($limit, $iterable, $dump)
    {
        $dumper = new LightVarDumper();
        $dumper2 = $dumper->setMaxChildren($limit);
        $this->assertSame($dumper2, $dumper);
        $this->assertSame($dump, $dumper->getDump($iterable));
    }

    public function providerMaxChildren()
    {
        return iterator_to_array(new ProviderMaxChildren());
    }

    /**
     * @dataProvider providerRecursive
     *
     * @param $var
     * @param string|bool $expectedDump
     */
    public function testRecursive($var, $expectedDump)
    {
        $dumper = new LightVarDumper();
        $dump = $dumper->getDump($var);
        $this->assertInternalType('string', $dump);
        if ($expectedDump !== false) {
            $this->assertSame($expectedDump, $dumper->getDump($var));
        }
    }

    public function providerRecursive()
    {
        return iterator_to_array(new ProviderRecursive());
    }
}
