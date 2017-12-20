<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
interface PropertyInterface
{
    /**
     * @return bool
     */
    public function isStatic();

    /**
     * @return bool
     */
    public function isVirtual();

    /**
     * @return bool
     */
    public function isPublic();

    /**
     * @return bool
     */
    public function isProtected();

    /**
     * @return bool
     */
    public function isPrivate();

    public function getValue();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDeclaringClass();
}
