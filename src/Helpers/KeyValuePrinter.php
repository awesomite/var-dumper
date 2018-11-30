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

use Awesomite\VarDumper\Strings\LinePart;
use Awesomite\VarDumper\Strings\PartInterface;
use Awesomite\VarDumper\Strings\Parts;

/**
 * @internal
 */
final class KeyValuePrinter
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

        $this->rows[] = array($key, $value, $strlen);
    }

    /**
     * @return PartInterface|null
     */
    public function flush()
    {
        if (!$this->rows) {
            return null;
        }

        $result = new Parts();
        foreach ($this->rows as $data) {
            list($key, $value, $strlen) = $data;
            $part = new LinePart($key . \str_pad('', $this->maxLength - $strlen, ' ') . $value);
            $result->appendPart($part);
        }
        $this->rows = array();
        $this->maxLength = 0;

        return $result;
    }
}
