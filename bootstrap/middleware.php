<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Package;

use Ixocreate\Admin\Package\Middleware\Factory\JsonBodyParamsFactory;
use Ixocreate\Application\Http\Middleware\MiddlewareConfigurator;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

/** @var MiddlewareConfigurator $middleware */

$middleware->addMiddleware(BodyParamsMiddleware::class, JsonBodyParamsFactory::class);
$middleware->addDirectory(__DIR__ . '/../src/Action', true);
$middleware->addDirectory(__DIR__ . '/../src/Middleware', true);
