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

use Awesomite\VarDumper\LightVarDumperProviders\ProviderDump;
use Awesomite\VarDumper\LightVarDumperProviders\ProviderDumpConstants;
use Awesomite\VarDumper\LightVarDumperProviders\ProviderMaxChildren;
use Awesomite\VarDumper\LightVarDumperProviders\ProviderMaxDepth;
use Awesomite\VarDumper\LightVarDumperProviders\ProviderMaxStringLength;
use Awesomite\VarDumper\LightVarDumperProviders\ProviderMultiLine;
use Awesomite\VarDumper\LightVarDumperProviders\ProviderRecursive;

/**
 * @internal
 */
class LightVarDumperTest extends BaseTestCase
{
    private $wasDumperReset = false;

    /**
     * @dataProvider providerDump
     *
     * @param mixed  $var
     * @param string $expectedDump
     */
    public function testDump($var, $expectedDump)
    {
        if (!$this->wasDumperReset) {
            $this->reinitAllDumpers();
        }

        $dumper = new LightVarDumper();
        if ('#' === $expectedDump[0]) {
            $this->assertRegExp($expectedDump, $dumper->getDump($var));
        } else {
            $this->assertSame($expectedDump, $dumper->getDump($var));
        }
    }

    public function providerDump()
    {
        return \array_merge(
            \iterator_to_array(new ProviderDump()),
            \iterator_to_array(new ProviderDumpConstants())
        );
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
        return \iterator_to_array(new ProviderMaxDepth());
    }

    /**
     * @dataProvider providerMaxStringLength
     *
     * @param int    $limit
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
        return \iterator_to_array(new ProviderMaxStringLength());
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
        return \iterator_to_array(new ProviderMaxChildren());
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
        if (false !== $expectedDump) {
            $this->assertSame($expectedDump, $dumper->getDump($var));
        }
    }

    public function providerRecursive()
    {
        return \iterator_to_array(new ProviderRecursive());
    }

    /**
     * @dataProvider providerMultiLine
     *
     * @param int    $stringLimit
     * @param int    $lineLimit
     * @param string $input
     * @param string $expected
     */
    public function testMultiLine($stringLimit, $lineLimit, $input, $expected)
    {
        $dumper = new LightVarDumper();
        $dumper
            ->setMaxStringLength($stringLimit)
            ->setMaxLineLength($lineLimit);
        $dump = $dumper->getDump($input);
        $this->assertInternalType('string', $dump);
        $this->assertSame($expected, $dump);
    }

    public function providerMultiLine()
    {
        return \iterator_to_array(new ProviderMultiLine());
    }

    /**
     * @dataProvider providerInvalidMaxDepth
     *
     * @expectedException \InvalidArgumentException
     *
     * @param $limit
     */
    public function testInvalidMaxDepth($limit)
    {
        $dumper = new LightVarDumper();
        $dumper->setMaxDepth($limit);
    }

    public function providerInvalidMaxDepth()
    {
        return array(
            array(0.1),
            array(0),
            array(-1),
            array('-1'),
            array(false),
        );
    }

    /**
     * @dataProvider providerInvalidMaxChildren
     *
     * @expectedException \InvalidArgumentException
     *
     * @param $limit
     */
    public function testInvalidMaxChildrenh($limit)
    {
        $dumper = new LightVarDumper();
        $dumper->setMaxChildren($limit);
    }

    public function providerInvalidMaxChildren()
    {
        return $this->providerInvalidMaxDepth();
    }

    /**
     * @dataProvider providerInvalidMaxStringLength
     *
     * @expectedException \InvalidArgumentException
     *
     * @param $limit
     */
    public function testInvalidMaxStringLength($limit)
    {
        $dumper = new LightVarDumper();
        $dumper->setMaxStringLength($limit);
    }

    public function providerInvalidMaxStringLength()
    {
        return array(
            array(0.1),
            array(0),
            array(-1),
            array('-1'),
            array(false),
            array(1),
        );
    }

    /**
     * @dataProvider providerInvalidMaxLineLength
     *
     * @expectedException \InvalidArgumentException
     *
     * @param $limit
     */
    public function testInvalidMaxLineLength($limit)
    {
        $dumper = new LightVarDumper();
        $dumper->setMaxLineLength($limit);
    }

    public function providerInvalidMaxLineLength()
    {
        return $this->providerInvalidMaxStringLength();
    }
    
    private function reinitAllDumpers()
    {
        $classes = array(
            'Awesomite\VarDumper\Subdumpers\AbstractObjectDumper',
            'Awesomite\VarDumper\Subdumpers\ArrayRecursiveDumper',
            'Awesomite\VarDumper\Subdumpers\ScalarDumper',
        );
        foreach ($classes as $class) {
            $reflectionInit = new \ReflectionProperty($class, 'inited');
            $reflectionInit->setAccessible(true);
            $reflectionInit->setValue(false);
            $this->wasDumperReset = true;
        }
    }
}
