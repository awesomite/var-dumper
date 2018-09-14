<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper\Subdumpers;

use Awesomite\VarDumper\Properties\Properties;
use Awesomite\VarDumper\Properties\PropertyInterface;

/**
 * @internal
 */
final class ObjectDebugInfoDumper extends AbstractObjectDumper
{
    public function supports($var)
    {
        return \is_object($var) && \method_exists($var, '__debugInfo');
    }

    public function dump($object)
    {
        $properties = $this->getProperties($object);
        $class = $this->getClassName($object);
        $debugInfoData = $this->getDebugInfoData($properties);
        if (false === $debugInfoData) {
            throw new VarNotSupportedException();
        }

        $this->container->getReferences()->push($object);

        $count = \count($debugInfoData);
        echo 'object(', $class, ') #', $this->container->getHasher()->getHashId($object), ' (', $count, ') {[';
        if ($count > 0) {
            echo "\n";
            ArrayBigDumper::dumpBody($debugInfoData, $this->container);
        }
        echo ']}';

        $this->container->getReferences()->pop();
    }

    /**
     * @param PropertyInterface[] $properties
     *
     * @return array|false
     */
    private function getDebugInfoData($properties)
    {
        if (1 !== \count($properties)) {
            return false;
        }

        if (isset($properties[0]) && Properties::PROPERTY_DEBUG_INFO === $properties[0]->getName()) {
            $value = $properties[0]->getValue();

            // check type of value for php < 5.6
            if (\is_array($value)) {
                return $value;
            }
        }

        return false;
    }
}
