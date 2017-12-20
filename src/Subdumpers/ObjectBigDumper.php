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
use Awesomite\VarDumper\Helpers\Symbols;
use Awesomite\VarDumper\LightVarDumper;
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

    public function supports(&$var)
    {
        return \is_object($var);
    }

    public function dump(&$object)
    {
        $this->depth->incr();
        $this->references->push($object);

        $properties = $this->getProperties($object);
        $class = $this->getClassName($object);

        $count = \count($properties);
        echo 'object(', $class, ') #', self::$hasher->getHashId($object), ' (', \count($properties), ') {';
        if ($count > 0) {
            echo "\n";
            $this->dumpProperties($properties);
        }
        echo '}' . "\n";

        $this->references->pop();
        $this->depth->decr();
    }

    /**
     * @param PropertyInterface[]|\Traversable $properties
     */
    private function dumpProperties($properties)
    {
        $limit = $this->config->getMaxChildren();
        $printer = new KeyValuePrinter();
        foreach ($properties as $property) {
            $propName = \str_replace("\n", Symbols::SYMBOL_NEW_LINE, $property->getName());
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
