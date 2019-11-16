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

use Awesomite\VarDumper\Config\AbstractConfig;
use Awesomite\VarDumper\Helpers\Container;
use Awesomite\VarDumper\Strings\PartInterface;

/**
 * @internal
 */
final class SubdumpersCollection
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var SubdumperInterface[]
     */
    private $subdumpers;

    /**
     * @param AbstractConfig            $config
     * @param null|SubdumperInterface[] $subdumpers
     */
    public function __construct(AbstractConfig $config, array $subdumpers = null)
    {
        $this->container = $container = new Container($config, $this);
        $this->subdumpers = null !== $subdumpers ? $subdumpers : array(
            new StringDumper($container),
            new NullDumper(),
            new ScalarDumper(),
            new ObjectRecursiveDumper($container),
            new ObjectTooDepthDumper($container),
            new ExceptionDumper($container),
            new ClosureDumper($container),
            new ObjectDebugInfoDumper($container),
            new ObjectBigDumper($container),
            new ArrayRecursiveDumper($container),
            new ArrayTooDepthDumper($container),
            new ArraySimpleViewDumper($container),
            new ArraySingleStringDumper($container),
            new ArrayBigDumper($container),
            new ResourceDumper(),
            new NativeDumper(),
        );
    }

    /**
     * @param $var
     *
     * @return PartInterface
     */
    public function dumpAsPart($var)
    {
        foreach ($this->subdumpers as $subdumper) {
            if ($subdumper->supports($var)) {
                $this->container->getDepth()->incr();
                $this->container->getReferences()->push($var);

                try {
                    $result = $subdumper->dump($var);
                    $this->container->getDepth()->decr();
                    $this->container->getReferences()->pop();

                    return $result;
                } catch (VarNotSupportedException $exception) {
                    $this->container->getDepth()->decr();
                    $this->container->getReferences()->pop();
                }
            }
        }

        throw new \RuntimeException(\sprintf('None of the subdumpers supports this variable [%s]', \is_object($var) ? \get_class($var) : \gettype($var)));
    }
}
