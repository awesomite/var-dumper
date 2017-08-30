<?php

namespace Awesomite\VarDumper\LightVarDumperProviders;

/**
 * @internal
 */
class TestDebugInfo
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function __debugInfo()
    {
        return $this->data;
    }
}
