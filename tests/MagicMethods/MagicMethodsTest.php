<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\MagicMethods;

use Awesomite\VarDumper\BaseTestCase;
use Awesomite\VarDumper\LightVarDumper;
use Awesomite\VarDumper\Properties\Properties;
use Awesomite\VarDumper\Properties\PropertyInterface;

/**
 * @internal
 */
class MagicMethodsTest extends BaseTestCase
{
    public function testDump()
    {
        $dumper = new LightVarDumper();
        $object = new RemovedProperty();

        $expected
            = <<<'EXPECTED'
(2) {
    private $a => “a”
    private $b => “b”
}
EXPECTED;
        $this->assertContains($expected, $dumper->dumpAsString($object));

        $object->without('a');
        $expected
            = <<<'EXPECTED'
(1) {
    private $b => “b”
}
EXPECTED;
        $this->assertContains($expected, $dumper->dumpAsString($object));

        $object->c = 'c';
        $expected
            = <<<'EXPECTED'
(2) {
    private $b => “b”
    $c =>         “c”
}
EXPECTED;
        $this->assertContains($expected, $dumper->dumpAsString($object));

        $getter = new GetterObject();
        $this->assertContains('(0) {}', $dumper->dumpAsString($getter));
    }

    /**
     * @dataProvider providerAbstractProperties
     *
     * @param          $object
     * @param string[] $expectedProperties
     */
    public function testAbstractProperties($object, array $expectedProperties)
    {
        $reader = new Properties($object);

        $this->assertSame(\count($expectedProperties), \count($reader->getProperties()));
        foreach ($reader->getProperties() as $property) {
            /** @var PropertyInterface $property */
            $this->assertContains($property->getName(), $expectedProperties);
        }
    }

    public function providerAbstractProperties()
    {
        return array(
            array(new RemovedProperty(), array('a', 'b')),
            array(new GetterObject(), array()),
            array(RemovedProperty::createWithout(array('a')), array('b')),
            array(RemovedProperty::createWithout(array('a'))->with('c'), array('b', 'c')),
            array(RemovedProperty::createWithout(array('a', 'b')), array()),
        );
    }
}
