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

use Symfony\Component\VarDumper\Cloner\ClonerInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;

final class SymfonyVarDumper implements VarDumperInterface
{
    /**
     * @var AbstractDumper
     */
    private $dumper;

    /**
     * @var ClonerInterface
     */
    private $cloner;

    public function __construct(AbstractDumper $dumper = null, ClonerInterface $cloner = null)
    {
        // @codeCoverageIgnoreStart
        if (!\class_exists('Symfony\Component\VarDumper\Dumper\AbstractDumper')) {
            throw new \RuntimeException('Package symfony/var-dumper is not installed, execute `composer require symfony/var-dumper`');
        }
        // @codeCoverageIgnoreEnd

        $this->dumper = $dumper ?: new CliDumper();
        $this->cloner = $cloner ?: new VarCloner();
    }

    public function dump($var)
    {
        $this->dumper->dump($this->cloner->cloneVar($var));
    }

    public function dumpAsString($var)
    {
        $stream = \tmpfile();
        $this->dumper->dump($this->cloner->cloneVar($var), $stream);
        \fseek($stream, 0);
        $result = '';
        while (!\in_array($buffer = \fread($stream, 1024), array(false, ''), true)) {
            $result .= $buffer;
        }
        \fclose($stream);

        return $result;
    }
}
