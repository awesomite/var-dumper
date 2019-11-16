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

use Awesomite\VarDumper\Helpers\FileNameDecorator;
use Awesomite\VarDumper\Properties\PropertyInterface;
use Awesomite\VarDumper\Properties\VarProperty;
use Awesomite\VarDumper\Strings\LinePart;
use Awesomite\VarDumper\Strings\Parts;

/**
 * @internal
 */
final class ClosureDumper extends AbstractObjectDumper
{
    public function supports($var)
    {
        return \is_object($var) && $var instanceof \Closure;
    }

    public function dump($closure)
    {
        $header = new LinePart(\sprintf(
            'object(%s) #%s {[',
            $this->getClassName($closure),
            $this->container->getHasher()->getHashId($closure)
        ));

        $result = new Parts();
        $result->appendPart($header);

        $body = ObjectBigDumper::dumpProperties($this->decorateProperties($this->getProperties($closure)), $this->container);
        $body->addIndent($this->container->getConfig()->getIndent());
        $result->appendPart($body);

        $result->appendPart(new LinePart(']}'));

        return $result;
    }

    /**
     * @param PropertyInterface[] $properties
     *
     * @return PropertyInterface[]
     */
    private function decorateProperties(array $properties)
    {
        $result = array();

        foreach ($properties as $property) {
            if ('filename' === $property->getName()) {
                $property = new VarProperty(
                    $property->getName(),
                    FileNameDecorator::decorateFileName($property->getValue(), $this->container->getConfig()->getMaxFileNameDepth()),
                    $property->isPublic() ? VarProperty::VISIBILITY_PUBLIC : ($property->isProtected() ? VarProperty::VISIBILITY_PROTECTED : VarProperty::VISIBILITY_PRIVATE),
                    $property->getDeclaringClass(),
                    $property->isStatic(),
                    $property->isVirtual()
                );
            }

            $result[] = $property;
        }

        return $result;
    }
}
