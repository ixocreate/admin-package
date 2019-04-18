<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Package;

use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Ixocreate\Admin\Package\Config\AdminConfig;
use Ixocreate\Admin\Package\Config\Client\ClientConfigGenerator;
use Ixocreate\Admin\Package\Config\Client\ClientConfigProviderSubManager;
use Ixocreate\Admin\Package\Config\Factory\AdminConfigFactory;
use Ixocreate\Admin\Package\Helper\Factory\ServerUrlHelperFactory;
use Ixocreate\Admin\Package\Helper\Factory\UrlHelperFactory;
use Ixocreate\Admin\Package\Helper\ServerUrlHelper;
use Ixocreate\Admin\Package\Helper\UrlHelper;
use Ixocreate\Admin\Package\Role\RoleSubManager;
use Ixocreate\Admin\Package\Router\AdminRouter;
use Ixocreate\Admin\Package\Router\Factory\AdminRouterFactory;
use Ixocreate\Admin\Package\Widget\DashboardWidgetProviderSubManager;

/** @var ServiceManagerConfigurator $serviceManager */

$serviceManager->addFactory(AdminConfig::class, AdminConfigFactory::class);
$serviceManager->addFactory(AdminRouter::class, AdminRouterFactory::class);
$serviceManager->addFactory(ServerUrlHelper::class, ServerUrlHelperFactory::class);
$serviceManager->addFactory(UrlHelper::class, UrlHelperFactory::class);
$serviceManager->addFactory(ClientConfigGenerator::class);

$serviceManager->addSubManager(RoleSubManager::class);
$serviceManager->addSubManager(ClientConfigProviderSubManager::class);
$serviceManager->addSubManager(DashboardWidgetProviderSubManager::class);
