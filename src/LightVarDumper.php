<?php

namespace Awesomite\VarDumper;

use Awesomite\VarDumper\Helpers\KeyValuePrinter;
use Awesomite\VarDumper\Helpers\Strings;
use Awesomite\VarDumper\Objects\Hasher;
use Awesomite\VarDumper\Objects\HasherInterface;
use Awesomite\VarDumper\Properties\Properties;
use Awesomite\VarDumper\Properties\PropertyInterface;

class LightVarDumper extends InternalVarDumper
{
    private $maxChildren = 20;

    private $maxStringLength = 200;

    private $depth = 0;

    private $maxDepth = 5;

    private $references = array();

    private $canCompareArrays;

    private $hasher = null;

    private $indent = '    ';

    /**
     * {@inheritdoc}
     */
    public function __construct($displayPlaceInCode = false, $stepShift = 0)
    {
        parent::__construct($displayPlaceInCode, $stepShift);
        $this->canCompareArrays = $this->canCompareArrayReferences();
    }

    public function dump($var)
    {
        if ($this->displayPlaceInCode && $this->depth === 0) {
            $this->dumpPlaceInCode(0);
        }

        if (is_string($var)) {
            $this->dumpString($var);
            return;
        }

        if (is_null($var)) {
            echo "NULL\n";
            return;
        }

        if (is_scalar($var)) {
            $this->dumpScalar($var);
            return;
        }

        if (is_object($var)) {
            $this->dumpObj($var);
            return;
        }

        if (is_array($var)) {
            $this->dumpArray($var);
            return;
        }

        if (is_resource($var)) {
            $this->dumpResource($var);
            return;
        }

        // @codeCoverageIgnoreStart
        // Theoretically the following lines are unnecessary
        $prev = $this->displayPlaceInCode;
        $this->displayPlaceInCode = false;
        parent::dump($var);
        $this->displayPlaceInCode = $prev;
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setMaxDepth($limit)
    {
        $this->maxDepth = $limit;

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setMaxStringLength($limit)
    {
        $this->maxStringLength = $limit;

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setMaxChildren($limit)
    {
        $this->maxChildren = $limit;

        return $this;
    }

    private function dumpResource($resource)
    {
        echo 'resource of type ', get_resource_type($resource), "\n";
    }

    private function dumpScalar($scalar)
    {
        echo var_export($scalar, true) . "\n";
    }

    private function dumpString($string)
    {
        $len = mb_strlen($string);
        $withSuffix = false;

        if ($len > $this->maxStringLength) {
            $string = mb_substr($string, 0, $this->maxStringLength);
            $withSuffix = true;
        }

        $containsNewLine = strpos($string, "\n") !== false;

        if ($withSuffix || $containsNewLine) {
            echo "string({$len})";
        }
        if (!$containsNewLine) {
            if ($withSuffix) {
                echo ' ';
            }
            echo Strings::SYMBOL_LEFT_QUOT, $string, Strings::SYMBOL_RIGHT_QUOT;
        } else {
            echo "\n", $this->indent, Strings::SYMBOL_CITE, ' ',
                str_replace("\n", "\n{$this->indent}" . Strings::SYMBOL_CITE . ' ', $string);
        }
        if ($withSuffix) {
            echo '...';
        }
        echo "\n";
    }

    private function dumpArray(&$array)
    {
        if ($this->canCompareArrays && in_array($array, $this->references, true)) {
            echo 'RECURSIVE array(' . count($array) . ")\n";
            return;
        }

        $this->depth++;
        $this->references[] = &$array;

        $limit = $this->maxChildren;
        $count = count($array);
        echo 'array(' . $count . ') {';
        if ($count > 0 && $this->depth > $this->maxDepth) {
            echo "...";
        } else if ($count > 0) {
            echo "\n";
            $printer = new KeyValuePrinter();
            foreach ($array as $key => $value) {
                $key = str_replace("\n", Strings::SYMBOL_NEW_LINE, $key);
                $valDump = $this->getDump($value);
                $valDump = mb_substr($valDump, 0, -1);
                if (strpos($valDump, "\n") === false) {
                    $printer->add("{$this->indent}[{$key}] => ", $valDump, mb_strlen("{$this->indent}[{$key}] => "));
                } else {
                    $printer->flush();
                    $valDump = str_replace("\n", "\n{$this->indent}{$this->indent}", $valDump);
                    echo "{$this->indent}[{$key}] =>\n{$this->indent}{$this->indent}$valDump\n";
                }

                if (!--$limit) {
                    $printer->flush();
                    if (count($array) > $this->maxChildren) {
                        echo "{$this->indent}(...)\n";
                    }
                    break;
                }
            }
            $printer->flush();
        }
        echo '}' . "\n";

        array_pop($this->references);
        $this->depth--;
    }

    private function dumpObj($object)
    {
        if (in_array($object, $this->references, true)) {
            echo 'RECURSIVE object(' . get_class($object) . ") #{$this->getHasher()->getHashId($object)}\n";
            return;
        }

        $this->depth++;
        $this->references[] = $object;

        $limit = $this->maxChildren;
        $propertiesIterator = new Properties($object);
        /** @var PropertyInterface[] $properties */
        $properties = $propertiesIterator->getProperties();
        $class = get_class($object);

        // @see https://github.com/facebook/hhvm/issues/7868
        // @codeCoverageIgnoreStart
        if (defined('HHVM_VERSION') && $object instanceof \Closure) {
            $class = 'Closure';
        }
        // @codeCoverageIgnoreEnd

        $count = count($properties);
        $hashId = $this->getHasher()->getHashId($object);
        echo 'object(' . $class . ') #' . $hashId . ' (' . count($properties) . ') {';
        if ($count > 0 && $this->depth > $this->maxDepth) {
            echo "...";
        } else if ($count > 0) {
            echo "\n";
            $printer = new KeyValuePrinter();
            foreach ($properties as $property) {
                $propName = str_replace("\n", Strings::SYMBOL_NEW_LINE, $property->getName());
                $key = "{$this->getTextTypePrefix($property)}\${$propName}";

                $valDump = $this->getDump($property->getValue());
                $valDump = mb_substr($valDump, 0, -1);
                if (strpos($valDump, "\n") === false) {
                    $printer->add("{$this->indent}{$key} => ", $valDump, mb_strlen("{$this->indent}{$key} => "));
                } else {
                    $printer->flush();
                    $valDump = str_replace("\n", "\n{$this->indent}{$this->indent}", $valDump);
                    echo "{$this->indent}{$key} =>\n{$this->indent}{$this->indent}$valDump\n";
                }

                if (!--$limit) {
                    $printer->flush();
                    if (count($properties) > $this->maxChildren) {
                        echo "{$this->indent}(...)\n";
                    }
                    break;
                }
            }
            $printer->flush();
        }
        echo '}' . "\n";

        array_pop($this->references);
        $this->depth--;
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

    /**
     * Code:
     *
     * $a = array();
     * $b = &$a;
     * $a[] = $b;
     * var_dump(in_array($b, array($a), true));
     *
     * throws fatal error "Nesting level too deep - recursive dependency?"
     * in PHP <= 5.3.14 || (PHP >= 5.4 && PHP <= 5.4.4)
     *
     * @codeCoverageIgnore
     */
    private function canCompareArrayReferences()
    {
        if (version_compare(PHP_VERSION, '5.4.5') >= 0) {
            return true;
        }

        // 5.4.* && < 5.4.5
        if (PHP_MINOR_VERSION === 4) {
            return false;
        }

        return version_compare(PHP_VERSION, '5.3.15') >= 0;
    }

    /**
     * @return HasherInterface
     */
    private function getHasher()
    {
        if (is_null($this->hasher)) {
            $this->hasher = new Hasher();
        }

        return $this->hasher;
    }
}
