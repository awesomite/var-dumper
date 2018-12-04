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

use Awesomite\VarDumper\LightVarDumperProviders\TestDebugInfo;
use Awesomite\VarDumper\Properties\ArrayObject;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/**
 * @internal
 */
final class VarDumpersTest extends BaseTestCase
{
    /**
     * @dataProvider providerDump
     *
     * @param VarDumperInterface $dumper
     * @param                    $var
     */
    public function testDump(VarDumperInterface $dumper, $var)
    {
        $this->assertInternalType('string', $dumper->dumpAsString($var));
    }

    public function providerDump()
    {
        $result = array();

        $dumpers = $this->createDumpers();
        foreach ($this->prepareVars() as $var) {
            foreach ($dumpers as $dumper) {
                $result[] = array($dumper, $var);
            }
        }

        return $result;
    }

    private function prepareVars()
    {
        return array(
            false,
            null,
            \tmpfile(),
            INF,
            new \stdClass(),
            new ArrayObject(\range(1, 100)),
            new \RuntimeException('My exception'),
            new TestDebugInfo(array('foo' => 'bar')),
        );
    }

    /**
     * @return VarDumperInterface[]
     */
    private function createDumpers()
    {
        return array(
            new LightVarDumper(),
            new LightVarDumper(true),
            new LightVarDumper(true, 1),
            new InternalVarDumper(),
            new InternalVarDumper(true),
            new InternalVarDumper(true, 1),
            new SymfonyVarDumper(),
            new SymfonyVarDumper(new CliDumper(), new VarCloner()),
        );
    }
}
