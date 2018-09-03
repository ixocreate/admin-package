<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var MiddlewareConfigurator $middleware */
use KiwiSuite\Admin\Middleware\Factory\JsonBodyParamsFactory;
use KiwiSuite\ApplicationHttp\Middleware\MiddlewareConfigurator;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

$middleware->addMiddleware(BodyParamsMiddleware::class, JsonBodyParamsFactory::class);
$middleware->addDirectory(__DIR__ . '/../src/Action', true);
$middleware->addDirectory(__DIR__ . '/../src/Middleware', true);
