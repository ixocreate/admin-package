<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\ServiceManager\ServiceManagerConfigurator $middlewareConfigurator */
use Interop\Http\Server\MiddlewareInterface;
use KiwiSuite\Admin\Middleware\AdminMiddleware;
use KiwiSuite\Admin\Middleware\Factory\AdminApplicationFactory;

$middlewareConfigurator->addFactory(AdminMiddleware::class, AdminApplicationFactory::class);
$middlewareConfigurator->addDirectory(__DIR__ . '/../src/Action', true, [MiddlewareInterface::class]);
$middlewareConfigurator->addDirectory(__DIR__ . '/../src/Middleware', true, [MiddlewareInterface::class]);
