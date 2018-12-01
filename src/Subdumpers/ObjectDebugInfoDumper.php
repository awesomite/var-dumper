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

use Awesomite\VarDumper\Strings\LinePart;
use Awesomite\VarDumper\Strings\Parts;

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
        $debugInfoData = $this->getDebugInfoData($object);
        if (null === $debugInfoData) {
            throw new VarNotSupportedException();
        }

        $class = $this->getClassName($object);

        $count = \count($debugInfoData);
        $header = new LinePart('object(' . $class . ') #' . $this->container->getHasher()->getHashId($object) . ' (' . $count . ') {[');
        $result = new Parts();
        $result->appendPart($header);
        if ($count > 0) {
            $body = ArrayBigDumper::dumpBody($debugInfoData, $this->container);
            $body->addIndent($this->container->getConfig()->getIndent());
            $result->appendPart($body);
            $result->appendPart(new LinePart(']}'));
        } else {
            $header->append(']}');
        }

        return $result;
    }

    /**
     * @param object $object
     *
     * @return array|null
     */
    private function getDebugInfoData($object)
    {
        $reflection = new \ReflectionMethod($object, '__debugInfo');
        if (!$reflection->isStatic() && $reflection->isPublic() && 0 === $reflection->getNumberOfParameters()) {
            $value = $reflection->invoke($object);

            // check type of value for php < 5.6
            if (\is_array($value)) {
                return $value;
            }
        }

        return null;
    }
}
