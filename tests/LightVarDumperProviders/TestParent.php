<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class TestParent
{
    protected static $protectedStatic = 'protected static value';

    private $private;

    public function setPrivate($value)
    {
        $this->private = $value;
    }
}
