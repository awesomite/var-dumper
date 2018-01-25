<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Helpers;

/**
 * @internal
 */
class KeyValuePrinter
{
    private $maxLength = 0;

    private $rows = [];

    /**
     * @param string $key
     * @param string $value
     */
    public function add($key, $value, $strlen)
    {
        if ($strlen > $this->maxLength) {
            $this->maxLength = $strlen;
        }

        $this->rows[] = [$key, $value, $strlen];
    }

    public function flush()
    {
        foreach ($this->rows as $data) {
            list($key, $value, $strlen) = $data;
            echo $key, \str_pad('', $this->maxLength - $strlen, ' '), $value, "\n";
        }
        $this->rows = [];
        $this->maxLength = 0;
    }
}
