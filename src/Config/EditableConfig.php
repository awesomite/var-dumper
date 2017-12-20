<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Config;

/**
 * @internal
 */
class EditableConfig extends Config
{
    public function setMaxChildren($limit)
    {
        $this->maxChildren = $limit;

        return $this;
    }

    public function setMaxDepth($limit)
    {
        $this->maxDepth = $limit;

        return $this;
    }

    public function setMaxStringLength($limit)
    {
        $this->maxStringLen = $limit;

        return $this;
    }

    public function setMaxLineLength($limit)
    {
        $this->maxLineLen = $limit;

        return $this;
    }
}
