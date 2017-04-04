<?php

namespace Awesomite\VarDumper\Properties;

/**
 * @internal
 */
interface PropertiesInterface
{
    /**
     * @return PropertyInterface[]|\Traversable
     */
    public function getProperties();
}
