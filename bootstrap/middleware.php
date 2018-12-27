<?php
declare(strict_types=1);

namespace Ixocreate\Admin;

/** @var MiddlewareConfigurator $middleware */
use Ixocreate\Admin\Middleware\Factory\JsonBodyParamsFactory;
use Ixocreate\ApplicationHttp\Middleware\MiddlewareConfigurator;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

$middleware->addMiddleware(BodyParamsMiddleware::class, JsonBodyParamsFactory::class);
$middleware->addDirectory(__DIR__ . '/../src/Action', true);
$middleware->addDirectory(__DIR__ . '/../src/Middleware', true);
