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
use KiwiSuite\Contract\ServiceManager\FactoryInterface;
use KiwiSuite\Contract\ServiceManager\ServiceManagerInterface;
use KiwiSuite\ProjectUri\ProjectUri;
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

        $uri = new Uri($config->get("admin.uri"));
        if (empty($uri->getHost())) {
            /** @var ProjectUri $projectUri */
            $projectUri = $container->get(ProjectUri::class);

            $uri = $uri->withHost($projectUri->getMainUrl()->getHost());
            $uri = $uri->withScheme($projectUri->getMainUrl()->getScheme());
            $uri = $uri->withPort($projectUri->getMainUrl()->getPort());
        }

        return new AdminConfig($config->get("admin"), $uri);
    }
}
