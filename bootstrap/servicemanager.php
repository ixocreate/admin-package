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
use KiwiSuite\Admin\Resource\Factory\ResourceSubManagerFactory;
use KiwiSuite\Admin\Resource\ResourceSubManager;
use KiwiSuite\Admin\Role\Factory\RoleSubManagerFactory;
use KiwiSuite\Admin\Role\RoleSubManager;
use KiwiSuite\Admin\Router\AdminRouter;
use KiwiSuite\Admin\Router\Factory\AdminRouterFactory;
use KiwiSuite\Template\Plates\PlatesRendererFactory;
use Zend\Expressive\Plates\PlatesRenderer;

$serviceManagerConfigurator->addFactory(AdminConfig::class, AdminConfigFactory::class);
$serviceManagerConfigurator->addFactory(AdminRouter::class, AdminRouterFactory::class);
$serviceManagerConfigurator->addFactory(ServerUrlHelper::class, ServerUrlHelperFactory::class);
$serviceManagerConfigurator->addFactory(UrlHelper::class, UrlHelperFactory::class);
$serviceManagerConfigurator->addFactory(PlatesRenderer::class, PlatesRendererFactory::class);

$serviceManagerConfigurator->addSubManager(RoleSubManager::class, RoleSubManagerFactory::class);
$serviceManagerConfigurator->addSubManager(ResourceSubManager::class, ResourceSubManagerFactory::class);
