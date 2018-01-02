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

use Awesomite\VarDumper\Config\Config;
use Awesomite\VarDumper\Helpers\IntValue;
use Awesomite\VarDumper\Helpers\KeyValuePrinter;
use Awesomite\VarDumper\Helpers\Stack;
use Awesomite\VarDumper\Helpers\Strings;
use Awesomite\VarDumper\LightVarDumper;
use Awesomite\VarDumper\Properties\Properties;
use Awesomite\VarDumper\Properties\PropertyInterface;

/**
 * @internal
 */
class ObjectBigDumper extends AbstractObjectDumper
{
    private $dumper;

    private $references;

    private $indent;

    private $depth;

    private $config;

    public function __construct(
        LightVarDumper $dumper,
        Stack $references,
        $indent,
        IntValue $depth,
        Config $config
    ) {
        $this->dumper = $dumper;
        $this->references = $references;
        $this->indent = $indent;
        $this->depth = $depth;
        $this->config = $config;
        parent::__construct();
    }

    public function supports($var)
    {
        return \is_object($var);
    }

    public function dump($object)
    {
        $this->depth->incr();
        $this->references->push($object);

        $properties = $this->getProperties($object);
        $class = $this->getClassName($object);
        list($isDebugInfo, $debugInfoData) = $this->getDebugInfoData($properties);

        $count = \count($isDebugInfo ? $debugInfoData : $properties);
        echo 'object(', $class, ') #', self::$hasher->getHashId($object), ' (', $count, ') {';
        if ($isDebugInfo) {
            echo '[';
        }
        if ($count > 0) {
            echo "\n";
            if ($isDebugInfo) {
                $this->dumpDebugInfoProperty($debugInfoData);
            } else {
                $this->dumpProperties($properties);
            }
        }
        echo($isDebugInfo ? ']' : ''), '}', "\n";

        $this->references->pop();
        $this->depth->decr();
    }

    /**
     * @param PropertyInterface[] $properties
     *
     * @return array
     */
    private function getDebugInfoData($properties)
    {
        if (1 !== \count($properties)) {
            return array(false, null);
        }

        if (isset($properties[0]) && Properties::PROPERTY_DEBUG_INFO === $properties[0]->getName()) {
            $value = $properties[0]->getValue();

            // check type of value for php < 5.6
            if (\is_array($value)) {
                return array(true, $value);
            }
        }

        return array(false, null);
    }

    private function dumpDebugInfoProperty(array $debugInfoData)
    {
        ArrayBigDumper::dumpBody($debugInfoData, $this->config, $this->dumper, $this->indent);
    }


    /**
     * @param PropertyInterface[]|\Traversable $properties
     */
    private function dumpProperties($properties)
    {
        $limit = $this->config->getMaxChildren();
        $printer = new KeyValuePrinter();
        foreach ($properties as $property) {
            $propName = Strings::prepareArrayKey($property->getName());
            $key = "{$this->getTextTypePrefix($property)}\${$propName}";

            $valDump = $this->dumper->getDump($property->getValue());
            $valDump = \mb_substr($valDump, 0, -1);
            if (false === \mb_strpos($valDump, "\n")) {
                $printer->add("{$this->indent}{$key} => ", $valDump, \mb_strlen("{$this->indent}{$key} => "));
            } else {
                $printer->flush();
                $valDump = \str_replace("\n", "\n{$this->indent}{$this->indent}", $valDump);
                echo "{$this->indent}{$key} =>\n{$this->indent}{$this->indent}$valDump\n";
            }

            if (!--$limit) {
                $printer->flush();
                if (\count($properties) > $this->config->getMaxChildren()) {
                    echo "{$this->indent}(...)\n";
                }
                break;
            }
        }
        $printer->flush();
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
