<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\ConfiguratorItem;

use KiwiSuite\Admin\Pipe\PipeConfig;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorItemInterface;
use KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator;

final class PipeConfiguratorItem implements ConfiguratorItemInterface
{
    /**
     * @return mixed
     */
    public function getConfigurator()
    {
        $pipeConfigurator = new PipeConfigurator();

        return $pipeConfigurator;
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'adminPipeConfigurator';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'admin_pipe.php';
    }

    /**
     * @param PipeConfigurator$configurator
     * @return \Serializable
     */
    public function getService($configurator): \Serializable
    {
        return new PipeConfig($configurator);
    }
}
