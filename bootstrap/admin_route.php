<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\ApplicationHttp\Route\RouteConfigurator $adminRouteConfigurator */
$adminRouteConfigurator->addGet('[/[{path}]]', \KiwiSuite\Admin\Action\IndexAction::class, "admin");

