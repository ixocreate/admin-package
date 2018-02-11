<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\ServiceManager\ServiceManagerConfigurator $middlewareConfigurator */
use KiwiSuite\Admin\Middleware\AdminMiddleware;
use KiwiSuite\Admin\Middleware\Factory\AdminApplicationFactory;
use KiwiSuite\Admin\Middleware\Factory\JsonBodyParamsFactory;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

$middlewareConfigurator->addFactory(AdminMiddleware::class, AdminApplicationFactory::class);
$middlewareConfigurator->addFactory(BodyParamsMiddleware::class, JsonBodyParamsFactory::class);
$middlewareConfigurator->addDirectory(__DIR__ . '/../src/Action', true, [MiddlewareInterface::class]);
$middlewareConfigurator->addDirectory(__DIR__ . '/../src/Middleware', true, [MiddlewareInterface::class]);
