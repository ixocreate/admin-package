<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator $adminPipeConfigurator */

use KiwiSuite\Admin\Middleware\Api\EnforceApiResponseMiddleware;
use KiwiSuite\Admin\Middleware\Api\ErrorMiddleware;
use KiwiSuite\Admin\Middleware\Api\SessionDataMiddleware;
use KiwiSuite\Admin\Middleware\Api\XsrfProtectionMiddleware;
use KiwiSuite\Admin\Middleware\CorsMiddleware;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;
use Zend\Expressive\Middleware\ImplicitHeadMiddleware;
use Zend\Expressive\Middleware\ImplicitOptionsMiddleware;

$adminPipeConfigurator->addGlobalMiddleware(CorsMiddleware::class);
$adminPipeConfigurator->addRoutingMiddleware(ImplicitHeadMiddleware::class);
$adminPipeConfigurator->addRoutingMiddleware(ImplicitOptionsMiddleware::class);

$adminPipeConfigurator->addPathMiddlewarePipe('/api', [
    EnforceApiResponseMiddleware::class,
    ErrorMiddleware::class,
    SessionDataMiddleware::class,
    XsrfProtectionMiddleware::class,
    BodyParamsMiddleware::class,
]);
