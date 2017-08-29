<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class ProviderMaxStringLength implements \IteratorAggregate
{
    public function getIterator()
    {
        $result = array(
            array(5, 'Hello world!', "string(12) «Hello»...\n"),
            array(2, 'Hello world!', "string(12) «He»...\n"),
            array(12, 'Hello world!', "«Hello world!»\n"),
            array(13, 'Hello world!', "«Hello world!»\n"),
        );

        return new \ArrayIterator($result);
    }
}
