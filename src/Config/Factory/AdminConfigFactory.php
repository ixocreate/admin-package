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

namespace KiwiSuite\Admin\Config\Factory;

use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Config\Config;
use KiwiSuite\ProjectUri\ProjectUri;
use KiwiSuite\ServiceManager\FactoryInterface;
use KiwiSuite\ServiceManager\ServiceManagerInterface;
use Zend\Diactoros\Uri;

final class AdminConfigFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        /** @var ProjectUri $projectUri */
        $projectUri = $container->get(ProjectUri::class);

        /**
         * make sure it's an absolute url
         */
        $uri = new Uri($config->get("admin.uri"));
        // if (empty($uri->getHost())) {
        //     $uri = new Uri($projectUri->getMainUrl() . $uri);
        // }

        return new AdminConfig($uri, $config->get("admin.project"));
    }
}
