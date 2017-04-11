<?php

namespace Awesomite\VarDumper;

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
    }
        // @codeCoverageIgnoreEnd

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
        $mapping = array(
            'boolean' => 'bool',
            'integer' => 'int',
        );
        $type = gettype($scalar);
        if (isset($mapping[$type])) {
            $type = $mapping[$type];
        }

        echo $type . '(' . var_export($scalar, true) . ")\n";
    }

    private function dumpString($string)
    {
        $len = strlen($string);
        $suffix = '';
        if ($len > $this->maxStringLength) {
            $string = substr($string, 0, $this->maxStringLength);
            $suffix = '...';
        }

        echo "string({$len}) ";
        var_export($string);
        echo $suffix . "\n";
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
        echo 'array(' . $count . ') {' . "\n";
        if ($count > 0 && $this->depth > $this->maxDepth) {
            echo "  ...\n";
        } else {
            foreach ($array as $key => $value) {
                $valDump = str_replace("\n", "\n  ", $this->getDump($value));
                $valDump = substr($valDump, 0, -2);
                echo "  [{$key}] =>\n  {$valDump}";
                if (!--$limit) {
                    if (count($array) > $this->maxChildren) {
                        echo "  (...)\n";
                    }
                    break;
                }
            }
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
        $count = count($properties);
        $hashId = $this->getHasher()->getHashId($object);
        echo 'object(' . $class . ') #' . $hashId . ' (' . count($properties) . ') {' . "\n";
        if ($count > 0 && $this->depth > $this->maxDepth) {
            echo "  ...\n";
        } else {
            foreach ($properties as $property) {
                $valDump = str_replace("\n", "\n  ", $this->getDump($property->getValue()));
                $valDump = substr($valDump, 0, -2);
                $declaringClass = '';
                if ($property->getDeclaringClass() !== $class) {
                    $declaringClass = " @{$property->getDeclaringClass()}";
                }
                $name = $property->getName();
                echo "  {$this->getTextTypePrefix($property)}\${$name}{$declaringClass} =>\n  {$valDump}";
                if (!--$limit) {
                    if (count($properties) > $this->maxChildren) {
                        echo "  (...)\n";
                    }
                    break;
                }
            }
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
        if (version_compare(PHP_VERSION, '5.4.5' >= 0)) {
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
