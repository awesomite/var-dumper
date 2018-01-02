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

/**
 * @internal
 */
class ResourceDumper implements SubdumperInterface
{
    public function supports($var)
    {
        return \is_resource($var);
    }

    public function dump($resource)
    {
        $id = $this->getResourceId($resource);
        if (false !== $id) {
            echo 'resource #', $id, ' of type ', \get_resource_type($resource), "\n";

            return;
        }

        // @codeCoverageIgnoreStart
        echo 'resource of type ', \get_resource_type($resource), "\n";
        return;
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param resource $resource
     *
     * @return int|false
     */
    private function getResourceId($resource)
    {
        if (\function_exists('get_resources')) {
            foreach (\get_resources(\get_resource_type($resource)) as $id => $val) {
                if ($val === $resource) {
                    return $id;
                }
                // @codeCoverageIgnoreStart
            }
        }
        // @codeCoverageIgnoreEnd

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
}
