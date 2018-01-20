<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator $adminPipeConfigurator */

use KiwiSuite\Admin\Middleware\Api\SessionDataMiddleware;
use KiwiSuite\Admin\Middleware\Api\XsrfProtectionMiddleware;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

$adminPipeConfigurator->addPathMiddlewarePipe('/api', [
    SessionDataMiddleware::class,
    XsrfProtectionMiddleware::class,
    BodyParamsMiddleware::class,
]);
