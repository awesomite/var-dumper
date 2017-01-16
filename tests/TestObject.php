<?php

namespace Awesomite\VarDumper;

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