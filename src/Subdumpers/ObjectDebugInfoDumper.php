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

use Awesomite\VarDumper\Properties\PropertyInterface;

/**
 * @internal
 */
class ObjectDebugInfoDumper extends AbstractObjectBigDumper
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

        $this->references->push($object);

        $count = \count($debugInfoData);
        echo 'object(', $class, ') #', self::$hasher->getHashId($object), ' (', $count, ') {[';
        if ($count > 0) {
            echo "\n";
            ArrayBigDumper::dumpBody($debugInfoData, $this->config, $this->dumper);
        }
        echo ']}', "\n";

        $this->references->pop();
    }

    /**
     * @param PropertyInterface[] $properties
     *
     * @return array
     */
    private function getDebugInfoData($properties)
    {
        return $properties[0]->getValue();
    }
}
