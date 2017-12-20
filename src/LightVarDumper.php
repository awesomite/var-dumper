<?php

/*
 * This file is part of the awesomite/var-dumper package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Awesomite\VarDumper;

use Awesomite\VarDumper\Config\EditableConfig;
use Awesomite\VarDumper\Helpers\IntValue;
use Awesomite\VarDumper\Helpers\Stack;
use Awesomite\VarDumper\Subdumpers\ArrayBigDumper;
use Awesomite\VarDumper\Subdumpers\ArrayRecursiveDumper;
use Awesomite\VarDumper\Subdumpers\ArraySimpleView;
use Awesomite\VarDumper\Subdumpers\ArraySingleElementDumper;
use Awesomite\VarDumper\Subdumpers\NullDumper;
use Awesomite\VarDumper\Subdumpers\ObjectBigDumper;
use Awesomite\VarDumper\Subdumpers\ObjectRecursiveDumper;
use Awesomite\VarDumper\Subdumpers\ObjectTooDepthArrayDumper;
use Awesomite\VarDumper\Subdumpers\ResourceDumper;
use Awesomite\VarDumper\Subdumpers\ScalarDumper;
use Awesomite\VarDumper\Subdumpers\StringDumper;
use Awesomite\VarDumper\Subdumpers\SubdumperInterface;
use Awesomite\VarDumper\Subdumpers\ArrayTooDepthDumper;

final class LightVarDumper extends InternalVarDumper
{
    const DEFAULT_MAX_CHILDREN = 20;
    const DEFAULT_MAX_STRING_LENGTH = 200;
    const DEFAULT_MAX_LINE_LENGTH = 130;
    const DEFAULT_MAX_DEPTH = 5;

    /**
     * @var EditableConfig
     */
    private $config;
    private $depth = null;

    private $indent = '    ';

    /**
     * @var SubdumperInterface[]
     */
    private $subdumpers = array();

    /**
     * {@inheritdoc}
     */
    public function __construct($displayPlaceInCode = false, $stepShift = 0)
    {
        parent::__construct($displayPlaceInCode, $stepShift);

        $this->config = new Config\EditableConfig(
            static::DEFAULT_MAX_CHILDREN,
            static::DEFAULT_MAX_DEPTH,
            static::DEFAULT_MAX_STRING_LENGTH,
            static::DEFAULT_MAX_LINE_LENGTH
        );
        $references = new Stack();
        $this->depth = new IntValue();

        $this->subdumpers = array(
            new StringDumper($this->indent, $this->config),
            new NullDumper(),
            new ScalarDumper(),
            new ObjectRecursiveDumper($references),
            new ObjectTooDepthArrayDumper($this->depth, $this->config),
            new ObjectBigDumper($this, $references, $this->indent, $this->depth, $this->config),
            new ArrayRecursiveDumper($references),
            new ArrayTooDepthDumper($this->depth, $this->config),
            new ArraySimpleView($this, $this->config),
            new ArraySingleElementDumper($this, $this->config),
            new ArrayBigDumper($this, $references, $this->indent, $this->depth, $this->config),
            new ResourceDumper()
        );
    }

    public function dump($var)
    {
        if ($this->displayPlaceInCode && 0 === $this->depth->getValue()) {
            $this->dumpPlaceInCode(0);
        }

        foreach ($this->subdumpers as $subdumper) {
            if ($subdumper->supports($var)) {
                $subdumper->dump($var);
                return;
            }
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
     * @param  int   $limit
     * @return $this
     */
    public function setMaxDepth($limit)
    {
        $this->config->setMaxDepth($limit);

        return $this;
    }

    /**
     * @param  int   $limit
     * @return $this
     */
    public function setMaxStringLength($limit)
    {
        $this->config->setMaxStringLength($limit);

        return $this;
    }

    /**
     * @param  int   $limit
     * @return $this
     */
    public function setMaxLineLength($limit)
    {
        $this->config->setMaxLineLength($limit);

        return $this;
    }

    /**
     * @param  int   $limit
     * @return $this
     */
    public function setMaxChildren($limit)
    {
        $this->config->setMaxChildren($limit);

        return $this;
    }
}
