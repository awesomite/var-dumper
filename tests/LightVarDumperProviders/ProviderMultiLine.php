<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

use Traversable;

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
        ));
    }

    private function getMultiline30()
    {
        $expected = <<<DATA
string(769)
    › Lorem ipsu
    › m dolor si
    › t amet, co
    › nsectetur 
    › adipiscing
    ›  elit. Pro
    › in nibh au
    › gue, susci
    › pit a, sce
    › lerisque s
    › ed, lacini
    › a in, mi. 
    › Cras vel l
    › orem. Etia
    › m pellente
    › sque aliqu
    › et tellus.
    ›  Phasellus
    ›  pharetra 
    › nulla ac d...

DATA;

        return array(200, 10, $this->getLoremIpsum(), $expected);
    }

    private function getMultiLine50()
    {
        $expected = <<<DATA
string(769)
    › Lorem ipsum dolor sit amet, consectetur adipiscing
    ›  elit. Proin nibh augue, suscipit a, scelerisque s
    › ed, lacinia in, mi. Cras vel lorem. Etiam pellente
    › sque aliquet tellus. Phasellus pharetra nulla ac d
    › iam. Quisque semper ...

DATA;

        return array(220, 50, $this->getLoremIpsum(), $expected);
    }

    private function getLoremIpsum()
    {
        return <<<DATA
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nibh augue, suscipit a, scelerisque sed, lacinia in, mi. Cras vel lorem. Etiam pellentesque aliquet tellus. Phasellus pharetra nulla ac diam. Quisque semper justo at risus. Donec venenatis, turpis vel hendrerit interdum, dui ligula ultricies purus, sed posuere libero dui id orci. Nam congue, pede vitae dapibus aliquet, elit magna vulputate arcu, vel tempus metus leo non est. Etiam sit amet lectus quis est congue mollis. Phasellus congue lacus eget neque. Phasellus ornare, ante vitae consectetuer consequat, purus sapien ultricies dolor, et mollis pede metus eget nisi. Praesent sodales velit quis augue. Cras suscipit, urna at aliquam rhoncus, urna quam viverra nisi, in interdum massa nibh nec erat.

DATA;
    }
}
