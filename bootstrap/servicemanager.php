<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\ServiceManager\ServiceManagerConfigurator $serviceManagerConfigurator */
use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Config\Factory\AdminConfigFactory;
use KiwiSuite\Admin\Helper\Factory\ServerUrlHelperFactory;
use KiwiSuite\Admin\Helper\Factory\UrlHelperFactory;
use KiwiSuite\Admin\Helper\ServerUrlHelper;
use KiwiSuite\Admin\Helper\UrlHelper;
use KiwiSuite\Admin\Router\AdminRouter;
use KiwiSuite\Admin\Router\Factory\AdminRouterFactory;

$serviceManagerConfigurator->addFactory(AdminConfig::class, AdminConfigFactory::class);
$serviceManagerConfigurator->addFactory(AdminRouter::class, AdminRouterFactory::class);
$serviceManagerConfigurator->addFactory(ServerUrlHelper::class, ServerUrlHelperFactory::class);
$serviceManagerConfigurator->addFactory(UrlHelper::class, UrlHelperFactory::class);
