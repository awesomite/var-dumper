<?php

namespace Awesomite\VarDumper;

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