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

use Awesomite\VarDumper\Helpers\Container;
use Awesomite\VarDumper\Helpers\KeyValuePrinter;
use Awesomite\VarDumper\Helpers\Strings;

/**
 * @internal
 */
final class ArrayBigDumper extends AbstractDumper
{
    public function supports($var)
    {
        return \is_array($var);
    }

    public function dump($array)
    {
        $this->container->getReferences()->push($array);

        $count = \count($array);
        echo 'array(' . $count . ') {';

        if ($count > 0) {
            echo "\n";
            static::dumpBody($array, $this->container);
        }

        echo '}';

        $this->container->getReferences()->pop();
    }

    public static function dumpBody(array $array, Container $container)
    {
        $nlOnEnd = $container->getPrintNlOnEnd();
        $nlOnEndPrev = $nlOnEnd->getValue();
        $nlOnEnd->setValue(false);

        $config = $container->getConfig();
        $dumper = $container->getDumper();

        $indent = $config->getIndent();
        $limit = $config->getMaxChildren();
        $printer = new KeyValuePrinter();
        foreach ($array as $key => $value) {
            $key = Strings::prepareArrayKey($key);
            $valDump = $dumper->dumpAsString($value);
            if (false === \mb_strpos($valDump, "\n")) {
                $printer->add("{$indent}[{$key}] => ", $valDump, \mb_strlen("{$indent}[{$key}] => "));
            } else {
                $printer->flush();
                $valDump = \str_replace("\n", "\n{$indent}{$indent}", $valDump);
                echo "{$indent}[{$key}] =>\n{$indent}{$indent}$valDump\n";
            }

            if (!--$limit) {
                $printer->flush();
                if (\count($array) > $config->getMaxChildren()) {
                    echo "{$indent}(...)\n";
                }
                break;
            }
        }
        $printer->flush();

        $nlOnEnd->setValue($nlOnEndPrev);
    }
}
