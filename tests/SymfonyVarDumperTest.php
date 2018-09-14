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

use Symfony\Component\VarDumper\Cloner\ClonerInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/**
 * @internal
 */
class SymfonyVarDumperTest extends BaseTestCase
{
    /**
     * @dataProvider providerAll
     *
     * @param                      $var
     * @param CliDumper|null       $dumper
     * @param ClonerInterface|null $cloner
     */
    public function testAll($var, CliDumper $dumper = null, ClonerInterface $cloner = null)
    {
        $dumper = new SymfonyVarDumper($dumper, $cloner);

        /** @var CliDumper $sfDumper */
        $sfDumper = $this->getProperty($dumper, 'dumper');

        /** @var ClonerInterface $vaCLoner */
        $varCloner = $this->getProperty($dumper, 'cloner');

        $stream = \tmpfile();
        $sfDumper->setOutput($stream);

        $abstractDumperMock = $this->getMockBuilder('Symfony\Component\VarDumper\Dumper\AbstractDumper')->getMock();
        $abstractDumperMock
            ->expects($this->exactly(2))
            ->method('dump')
            ->willReturnCallback(function () use ($sfDumper) {
                return \call_user_func_array(array($sfDumper, 'dump'), \func_get_args());
            });
        $this->setProperty($dumper, 'dumper', $abstractDumperMock);

        $varClonerMock = $this->getMockBuilder('Symfony\Component\VarDumper\Cloner\ClonerInterface')->getMock();
        $varClonerMock
            ->expects($this->exactly(2))
            ->method('cloneVar')
            ->willReturnCallback(function () use ($varCloner) {
                return \call_user_func_array(array($varCloner, 'cloneVar'), \func_get_args());
            });
        $this->setProperty($dumper, 'cloner', $varClonerMock);

        $dumper->dump($var);
        $output = $this->readStream($stream);
        $this->assertInternalType('string', $output);
        $this->assertNotSame('', $output);

        $string = $dumper->dumpAsString($var);
        $this->assertInternalType('string', $string);
        $this->assertNotSame('', $string);
    }

    public function providerAll()
    {
        return array(
            array(null),
            array(new \stdClass()),
            array(false, new CliDumper()),
            array(\range(1, 10), new CliDumper(), new VarCloner()),
        );
    }

    /**
     * @param resource $stream
     *
     * @return string string
     */
    private function readStream($stream)
    {
        \fseek($stream, 0);
        $result = '';
        while (!\in_array($buffer = \fread($stream, 1024), array(false, ''), true)) {
            $result .= $buffer;
        }

        return $result;
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param $value
     */
    private function setProperty($object, $propertyName, $value)
    {
        $reflectionClass = new \ReflectionClass($object);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }

    /**
     * @param object $object
     * @param string $propertyName
     *
     * @return mixed
     */
    private function getProperty($object, $propertyName)
    {
        $reflectionClass = new \ReflectionClass($object);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($object);
    }
}
