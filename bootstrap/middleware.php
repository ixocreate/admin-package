<?php
declare(strict_types=1);

namespace Ixocreate\Package\Admin;

use Ixocreate\Package\Admin\Middleware\Factory\JsonBodyParamsFactory;
use Ixocreate\Application\Http\Middleware\MiddlewareConfigurator;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

/** @var MiddlewareConfigurator $middleware */

$middleware->addMiddleware(BodyParamsMiddleware::class, JsonBodyParamsFactory::class);
$middleware->addDirectory(__DIR__ . '/../src/Action', true);
$middleware->addDirectory(__DIR__ . '/../src/Middleware', true);
