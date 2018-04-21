<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\MagicMethods;

/**
 * @internal
 */
class RemovedProperty
{
    private $a = 'a';

    private $b = 'b';

    public function without($name)
    {
        unset($this->$name);

        return $this;
    }

    public function with($name, $value = null)
    {
        $this->$name = $value;

        return $this;
    }

    /**
     * @param string[] $props
     *
     * @return static
     */
    public static function createWithout(array $props)
    {
        $result = new static();
        foreach ($props as $prop) {
            $result->without($prop);
        }

        return $result;
    }
}
