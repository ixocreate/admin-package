<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Admin\Middleware\Factory\JsonBodyParamsFactory;
use Ixocreate\Application\Http\Middleware\MiddlewareConfigurator;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;

/** @var MiddlewareConfigurator $middleware */
$middleware->addMiddleware(BodyParamsMiddleware::class, JsonBodyParamsFactory::class);
$middleware->addDirectory(__DIR__ . '/../src/Action', true);
$middleware->addDirectory(__DIR__ . '/../src/Middleware', true);
