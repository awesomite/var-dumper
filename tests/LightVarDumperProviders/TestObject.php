<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class TestObject extends TestParent
{
    public static $static = 'static value';

    public $public;

    protected $protected;

    public function setProtected($value)
    {
        $this->protected = $value;
    }
}
