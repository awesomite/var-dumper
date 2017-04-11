<?php

namespace Awesomite\VarDumper\Objects;

use Awesomite\VarDumper\BaseTestCase;

class HasherTest extends BaseTestCase
{
    public function testHash()
    {
        $hasher = new Hasher();

        $object1 = new \stdClass();
        $object2 = clone $object1;
        $object3 = new \stdClass();

        $hash1 = $hasher->getHashId($object1);
        $hash2 = $hasher->getHashId($object2);
        $hash3 = $hasher->getHashId($object3);

        $this->assertNotEquals($hash1, $hash2);
        $this->assertNotEquals($hash2, $hash3);
        $this->assertNotEquals($hash1, $hash3);

        $object1->property = 'value';
        $this->assertEquals($hash1, $hasher->getHashId($object1));

        // "Once the object is destroyed, its hash may be reused for other objects."
        // @see http://php.net/manual/en/function.spl-object-hash.php
        // $this->assertEquals($hasher->getHashId(new \stdClass()), $hasher->getHashId(new \stdClass()));
    }

    /**
     * @dataProvider providerInvalidException
     * @expectedException \InvalidArgumentException
     *
     * @param $object
     */
    public function testInvalidArgument($object)
    {
        $hasher = new Hasher();
        $hasher->getHashId($object);
    }

    public function providerInvalidException()
    {
        return array(
            array(1),
            array(null),
            array(false),
            array('hello'),
            array(tmpfile()),
        );
    }
}
