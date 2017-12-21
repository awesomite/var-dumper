<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class ProviderMultiLine implements \IteratorAggregate
{
    public function getIterator()
    {
        return new \ArrayIterator(array(
            30 => $this->getMultiline30(),
            50 => $this->getMultiLine50(),
            'without_dots' => $this->getMultilineWithoutDots(),
            'broken_dots' => $this->getMultilineWithBrokenDots(),
            'new_lines' => $this->getMultilineWithNewLines(),
        ));
    }

    private function getMultiline30()
    {
        $expected = <<<DATA
string(769)
    › Lorem 
    › ipsum 
    › dolor sit 
    › amet, 
    › consectetu
    › r 
    › adipiscing
    ›  elit. 
    › Proin nibh
    ›  augue, 
    › suscipit 
    › a, 
    › scelerisqu
    › e sed, 
    › lacinia 
    › in, mi. 
    › Cras vel 
    › lorem. 
    › Etiam 
    › pellentesq
    › ue aliquet
    ›  tellus. 
    › Phasellus 
    › pharetra 
    › nulla ac 
    › d...

DATA;

        return array(200, 10, $this->getLoremIpsum(), $expected);
    }

    private function getMultiLine50()
    {
        $expected = <<<DATA
string(769)
    › Lorem ipsum dolor sit amet, consectetur adipiscing
    ›  elit. Proin nibh augue, suscipit a, scelerisque 
    › sed, lacinia in, mi. Cras vel lorem. Etiam 
    › pellentesque aliquet tellus. Phasellus pharetra 
    › nulla ac diam. Quisque semper ...

DATA;

        return array(220, 50, $this->getLoremIpsum(), $expected);
    }

    private function getMultilineWithoutDots()
    {
        $input = \str_pad('', 20, 'a');
        $expected = <<<'EXPECTED'
string(20)
    › aaaaa
    › aaaaa
    › aaaaa
    › aaaaa

EXPECTED;

        return array(20, 5, $input, $expected);
    }

    private function getMultilineWithBrokenDots()
    {
        $input = \str_pad('', 20, 'a');
        $expected = <<<'EXPECTED'
string(20)
    › aaaaa
    › aaaaa
    › aaaaa
    › aaaa.
    › ..

EXPECTED;

        return array(19, 5, $input, $expected);
    }

    private function getMultilineWithNewLines()
    {
        $input = "Hello\n\n\nworld!";
        $expected = <<< 'EXCPECTED'
string(14)
    › Hello
    › 
    › 
    › world!

EXCPECTED;


        return array(100, 20, $input, $expected);
    }

    private function getLoremIpsum()
    {
        return <<<DATA
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nibh augue, suscipit a, scelerisque sed, lacinia in, mi. Cras vel lorem. Etiam pellentesque aliquet tellus. Phasellus pharetra nulla ac diam. Quisque semper justo at risus. Donec venenatis, turpis vel hendrerit interdum, dui ligula ultricies purus, sed posuere libero dui id orci. Nam congue, pede vitae dapibus aliquet, elit magna vulputate arcu, vel tempus metus leo non est. Etiam sit amet lectus quis est congue mollis. Phasellus congue lacus eget neque. Phasellus ornare, ante vitae consectetuer consequat, purus sapien ultricies dolor, et mollis pede metus eget nisi. Praesent sodales velit quis augue. Cras suscipit, urna at aliquam rhoncus, urna quam viverra nisi, in interdum massa nibh nec erat.

DATA;
    }
}
