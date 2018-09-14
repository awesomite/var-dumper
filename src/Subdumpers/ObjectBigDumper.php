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

use Awesomite\VarDumper\Helpers\KeyValuePrinter;
use Awesomite\VarDumper\Helpers\Strings;
use Awesomite\VarDumper\Properties\PropertyInterface;

/**
 * @internal
 */
final class ObjectBigDumper extends AbstractObjectDumper
{
    public function supports($var)
    {
        return \is_object($var);
    }

    public function dump($object)
    {
        $this->container->getReferences()->push($object);

        $properties = $this->getProperties($object);
        $class = $this->getClassName($object);

        $count = \count($properties);
        echo 'object(', $class, ') #', $this->container->getHasher()->getHashId($object), ' (', $count, ') {';
        if ($count > 0) {
            echo "\n";
            $this->dumpProperties($properties);
        }
        echo '}';

        $this->container->getReferences()->pop();
    }


    /**
     * @param PropertyInterface[] $properties
     */
    private function dumpProperties($properties)
    {
        $nlOnEnd = $this->container->getPrintNlOnEnd();
        $nlOnEndPrev = $nlOnEnd->getValue();
        $nlOnEnd->setValue(false);

        $limit = $this->container->getConfig()->getMaxChildren();
        $printer = new KeyValuePrinter();
        $indent = $this->container->getConfig()->getIndent();
        foreach ($properties as $property) {
            $propName = Strings::prepareArrayKey($property->getName());
            $key = "{$this->getTextTypePrefix($property)}\${$propName}";

            $valDump = $this->container->getDumper()->dumpAsString($property->getValue());
            if (false === \mb_strpos($valDump, "\n")) {
                $printer->add("{$indent}{$key} => ", $valDump, \mb_strlen("{$indent}{$key} => "));
            } else {
                $printer->flush();
                $valDump = \str_replace("\n", "\n{$indent}{$indent}", $valDump);
                echo "{$indent}{$key} =>\n{$indent}{$indent}$valDump\n";
            }

            if (!--$limit) {
                $printer->flush();
                if (\count($properties) > $this->container->getConfig()->getMaxChildren()) {
                    echo "{$indent}(...)\n";
                }
                break;
            }
        }
        $printer->flush();

        $nlOnEnd->setValue($nlOnEndPrev);
    }

    private function getTextTypePrefix(PropertyInterface $property)
    {
        if ($property->isVirtual()) {
            return '';
        }

        $suffix = $property->isStatic() ? 'static ' : '';

        if ($property->isPublic()) {
            return 'public ' . $suffix;
        }

        if ($property->isProtected()) {
            return 'protected ' . $suffix;
        }

        return 'private ' . $suffix;
    }
}
