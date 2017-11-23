<?php

namespace Awesomite\VarDumper;

use Awesomite\VarDumper\Helpers\KeyValuePrinter;
use Awesomite\VarDumper\Helpers\Symbols;
use Awesomite\VarDumper\Objects\HasherFactory;
use Awesomite\VarDumper\Objects\HasherInterface;
use Awesomite\VarDumper\Properties\Properties;
use Awesomite\VarDumper\Properties\PropertyInterface;

class LightVarDumper extends InternalVarDumper
{
    const DEFAULT_MAX_CHILDREN = 20;
    const DEFAULT_MAX_STRING_LENGTH = 200;
    const DEFAULT_MAX_LINE_LENGTH = 130;
    const DEFAULT_MAX_DEPTH = 5;

    private static $floatMapping = array(
        'M_PI' => M_PI,
        'M_E' => M_E,
        'M_LOG2E' => M_LOG2E,
        'M_LOG10E' => M_LOG10E,
        'M_LN2' => M_LN2,
        'M_LN10' => M_LN10,
        'M_PI_2' => M_PI_2,
        'M_PI_4' => M_PI_4,
        'M_1_PI' => M_1_PI,
        'M_2_PI' => M_2_PI,
        'M_SQRTPI' => M_SQRTPI,
        'M_2_SQRTPI' => M_2_SQRTPI,
        'M_SQRT2' => M_SQRT2,
        'M_SQRT3' => M_SQRT3,
        'M_SQRT1_2' => M_SQRT1_2,
        'M_LNPI' => M_LNPI,
        'M_EULER' => M_EULER,
    );

    private static $intMapping = array(
        PHP_INT_MAX => 'PHP_INT_MAX',
    );

    private static $inited = false;

    private static $canCompareArrays;

    /**
     * @var HasherInterface
     */
    private static $hasher;

    private $maxChildren = self::DEFAULT_MAX_CHILDREN;

    private $maxStringLength = self::DEFAULT_MAX_STRING_LENGTH;

    private $maxLineLength = self::DEFAULT_MAX_LINE_LENGTH;

    private $maxDepth = self::DEFAULT_MAX_DEPTH;

    private $depth = 0;

    private $references = array();

    private $indent = '    ';

    /**
     * {@inheritdoc}
     */
    public function __construct($displayPlaceInCode = false, $stepShift = 0)
    {
        parent::__construct($displayPlaceInCode, $stepShift);
        self::init();
    }

    public function dump($var)
    {
        if ($this->displayPlaceInCode && 0 === $this->depth) {
            $this->dumpPlaceInCode(0);
        }

        if (\is_string($var)) {
            $this->dumpString($var);
            return;
        }

        if (\is_null($var)) {
            echo "NULL\n";
            return;
        }

        if (\is_scalar($var)) {
            $this->dumpScalar($var);
            return;
        }

        if (\is_object($var)) {
            $this->dumpObj($var);
            return;
        }

        if (\is_array($var)) {
            $this->dumpArray($var);
            return;
        }

        if (\is_resource($var)) {
            $this->dumpResource($var);
            return;
        }

        // @codeCoverageIgnoreStart
        // Theoretically the following lines are unnecessary
        $prev = $this->displayPlaceInCode;
        $this->displayPlaceInCode = false;
        parent::dump($var);
        $this->displayPlaceInCode = $prev;
        return;
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
     * @param $limit
     * @return $this
     */
    public function setMaxLineLength($limit)
    {
        $this->maxLineLength = $limit;

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

    private static function init()
    {
        if (self::$inited) {
            return;
        }

        self::$intMapping[-PHP_INT_MAX - 1] = 'PHP_INT_MIN';

        self::$canCompareArrays = self::canCompareArrayReferences();

        self::$hasher = HasherFactory::create();

        self::$inited = true;
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
    private static function canCompareArrayReferences()
    {
        if (\version_compare(PHP_VERSION, '5.4.5') >= 0) {
            return true;
        }

        // 5.4.* && < 5.4.5
        if (PHP_MINOR_VERSION === 4) {
            return false;
        }

        return \version_compare(PHP_VERSION, '5.3.15') >= 0;
    }

    private function dumpResource($resource)
    {
        $id = $this->getResourceId($resource);
        if (false !== $id) {
            echo 'resource #', $id, ' of type ', \get_resource_type($resource), "\n";
            return;
        }

        // @codeCoverageIgnoreStart
        echo 'resource of type ', \get_resource_type($resource), "\n";
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param resource $resource
     * @return int|false
     */
    private function getResourceId($resource)
    {
        if (\function_exists('get_resources')) {
            foreach (\get_resources(\get_resource_type($resource)) as  $id => $val) {
                if ($val === $resource) {
                    return $id;
                }
            }
        }

        \ob_start();
        \var_dump($resource);
        $contents = \ob_get_contents();
        \ob_end_clean();

        $matches = array();
        if (\preg_match('#resource\((?<id>[0-9]+)\) of type#', $contents, $matches)) {
            return $matches['id'];
        }

        // @codeCoverageIgnoreStart
        $contents = \strip_tags($contents);
        $matches = array();
        if (\preg_match('#resource\((?<id>[0-9]+),#', $contents, $matches)) {
            return $matches['id'];
        }

        return false;
        // @codeCoverageIgnoreEnd
    }

    private function dumpScalar($scalar)
    {
        if (\is_float($scalar)) {
            foreach (self::$floatMapping as $key => $value) {
                if ($value === $scalar) {
                    echo $key, "\n";
                    return;
                }
            }
        }

        if (\is_int($scalar) && \array_key_exists($scalar, self::$intMapping)) {
            echo self::$intMapping[$scalar], "\n";
            return;
        }

        echo \var_export($scalar, true), "\n";
    }

    private function dumpString($string)
    {
        $len = \mb_strlen($string);
        $withPrefix = false;
        $withSuffix = false;

        $containsNewLine = false !== \mb_strpos($string, "\n");
        $isMultiLine = $len > $this->maxLineLength || $containsNewLine;

        if ($isMultiLine) {
            $withPrefix = true;
        }

        if ($len > $this->maxStringLength) {
            $string = \mb_substr($string, 0, $this->maxStringLength);
            $withPrefix = true;
            $withSuffix = true;
        }

        if ($withPrefix || $containsNewLine) {
            echo "string({$len})";
        }
        if (!$isMultiLine) {
            if ($withPrefix) {
                echo ' ';
            }
            echo Symbols::SYMBOL_LEFT_QUOT, $string, Symbols::SYMBOL_RIGHT_QUOT;
            if ($withSuffix) {
                echo '...';
            }
            echo "\n";
        } else {
            foreach (\explode("\n", $string) as $line) {
                while (true) {
                    if (\mb_strlen($line) > $this->maxLineLength) {
                        $storage = \mb_substr($line, $this->maxLineLength);
                        $line = \mb_substr($line, 0, $this->maxLineLength);
                    } else {
                        $storage = '';
                    }
                    echo "\n", $this->indent, Symbols::SYMBOL_CITE, ' ', $line;
                    if ('' === $storage) {
                        break;
                    }
                    $line = $storage;
                }
            }
            if ($withSuffix) {
                echo '...';
            }
            echo "\n";
        }
    }

    private function dumpArray(&$array)
    {
        if (self::$canCompareArrays && \in_array($array, $this->references, true)) {
            echo 'RECURSIVE array(' . \count($array) . ")\n";
            return;
        }

        $this->depth++;
        $this->references[] = &$array;

        $limit = $this->maxChildren;
        $count = \count($array);
        echo 'array(' . $count . ') {';

        $done = false;

        if ($count > 0 && $this->depth > $this->maxDepth) {
            echo "...";
            $done = true;
        }

        if (
            !$done
            && 1 === $count
            && \array_key_exists(0, $array)
            && (!\is_array($array[0]) && !\is_object($array[0]))
            && (
                !\is_string($array[0])
                || (\mb_strlen($array[0])) <= $this->maxLineLength && false === \mb_strpos($array[0], "\n")
            )
        ) {
            echo \rtrim($this->getDump($array[0]), "\n");
            $done = true;
        }

        if (!$done && $count > 0) {
            echo "\n";
            $printer = new KeyValuePrinter();
            foreach ($array as $key => $value) {
                $key = \str_replace("\n", Symbols::SYMBOL_NEW_LINE, $key);
                $valDump = $this->getDump($value);
                $valDump = \mb_substr($valDump, 0, -1);
                if (false === \mb_strpos($valDump, "\n")) {
                    $printer->add("{$this->indent}[{$key}] => ", $valDump, \mb_strlen("{$this->indent}[{$key}] => "));
                } else {
                    $printer->flush();
                    $valDump = \str_replace("\n", "\n{$this->indent}{$this->indent}", $valDump);
                    echo "{$this->indent}[{$key}] =>\n{$this->indent}{$this->indent}$valDump\n";
                }

                if (!--$limit) {
                    $printer->flush();
                    if (\count($array) > $this->maxChildren) {
                        echo "{$this->indent}(...)\n";
                    }
                    break;
                }
            }
            $printer->flush();
        }

        echo '}' . "\n";

        \array_pop($this->references);
        $this->depth--;
    }

    private function dumpObj($object)
    {
        if (\in_array($object, $this->references, true)) {
            echo 'RECURSIVE object(', \get_class($object), ') #', self::$hasher->getHashId($object), "\n";
            return;
        }

        $this->depth++;
        $this->references[] = $object;

        $limit = $this->maxChildren;
        $propertiesIterator = new Properties($object);
        /** @var PropertyInterface[] $properties */
        $properties = $propertiesIterator->getProperties();
        $class = \get_class($object);

        // @see https://github.com/facebook/hhvm/issues/7868
        // @codeCoverageIgnoreStart
        if (\defined('HHVM_VERSION') && $object instanceof \Closure) {
            $class = 'Closure';
        }
        // @codeCoverageIgnoreEnd

        $count = \count($properties);
        echo 'object(', $class, ') #', self::$hasher->getHashId($object), ' (', \count($properties), ') {';
        if ($count > 0 && $this->depth > $this->maxDepth) {
            echo "...";
        } elseif ($count > 0) {
            echo "\n";
            $printer = new KeyValuePrinter();
            foreach ($properties as $property) {
                $propName = \str_replace("\n", Symbols::SYMBOL_NEW_LINE, $property->getName());
                $key = "{$this->getTextTypePrefix($property)}\${$propName}";

                $valDump = $this->getDump($property->getValue());
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
                    if (\count($properties) > $this->maxChildren) {
                        echo "{$this->indent}(...)\n";
                    }
                    break;
                }
            }
            $printer->flush();
        }
        echo '}' . "\n";

        \array_pop($this->references);
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
}
