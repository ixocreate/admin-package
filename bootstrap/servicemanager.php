<?php
declare(strict_types=1);

namespace Ixocreate\Package\Admin;

use Ixocreate\Package\Admin\Config\AdminConfig;
use Ixocreate\Package\Admin\Config\Client\ClientConfigGenerator;
use Ixocreate\Package\Admin\Config\Client\ClientConfigProviderSubManager;
use Ixocreate\Package\Admin\Config\Factory\AdminConfigFactory;
use Ixocreate\Package\Admin\Helper\Factory\ServerUrlHelperFactory;
use Ixocreate\Package\Admin\Helper\Factory\UrlHelperFactory;
use Ixocreate\Package\Admin\Helper\ServerUrlHelper;
use Ixocreate\Package\Admin\Helper\UrlHelper;
use Ixocreate\Package\Admin\Role\RoleSubManager;
use Ixocreate\Package\Admin\Router\AdminRouter;
use Ixocreate\Package\Admin\Router\Factory\AdminRouterFactory;
use Ixocreate\Package\Admin\Widget\DashboardWidgetProviderSubManager;

/** @var \Ixocreate\ServiceManager\ServiceManagerConfigurator $serviceManager */

$serviceManager->addFactory(AdminConfig::class, AdminConfigFactory::class);
$serviceManager->addFactory(AdminRouter::class, AdminRouterFactory::class);
$serviceManager->addFactory(ServerUrlHelper::class, ServerUrlHelperFactory::class);
$serviceManager->addFactory(UrlHelper::class, UrlHelperFactory::class);
$serviceManager->addFactory(ClientConfigGenerator::class);

$serviceManager->addSubManager(RoleSubManager::class);
$serviceManager->addSubManager(ClientConfigProviderSubManager::class);
$serviceManager->addSubManager(DashboardWidgetProviderSubManager::class);
