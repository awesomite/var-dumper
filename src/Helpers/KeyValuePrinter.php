<?php

namespace Awesomite\VarDumper\Helpers;

/**
 * @internal
 */
class KeyValuePrinter
{
    private $maxLength = 0;

    private $rows = array();

    /**
     * @param string $key
     * @param string $value
     */
    public function add($key, $value, $strlen)
    {
        if ($strlen > $this->maxLength) {
            $this->maxLength = $strlen;
        }

        $this->rows[] = array($key, $value);
    }

    public function flush()
    {
        foreach ($this->rows as $data) {
            list($key, $value) = $data;
            echo \str_pad($key, $this->maxLength, ' '), $value, "\n";
        }
        $this->rows = array();
        $this->maxLength = 0;
    }
}
