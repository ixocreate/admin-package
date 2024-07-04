<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Admin\Action\Api;
use Ixocreate\Admin\Action\Auth;
use Ixocreate\Admin\Action\IndexAction;
use Ixocreate\Admin\Action\StaticAction;
use Ixocreate\Admin\Middleware\Api\ActivityMiddleware;
use Ixocreate\Admin\Middleware\Api\AuthorizationGuardMiddleware;
use Ixocreate\Admin\Middleware\Api\AuthorizationMiddleware;
use Ixocreate\Admin\Middleware\Api\EnforceApiResponseMiddleware;
use Ixocreate\Admin\Middleware\Api\ErrorMiddleware;
use Ixocreate\Admin\Middleware\Api\SessionDataMiddleware;
use Ixocreate\Admin\Middleware\Api\UserMiddleware;
use Ixocreate\Admin\Middleware\Api\XsrfProtectionMiddleware;
use Ixocreate\Admin\Middleware\Factory\JsonBodyParamsFactory;
use Ixocreate\Application\Http\Middleware\MiddlewareConfigurator;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;

/** @var MiddlewareConfigurator $middleware */
$middleware->addMiddleware(BodyParamsMiddleware::class, JsonBodyParamsFactory::class);
$middleware->addMiddleware(ActivityMiddleware::class);
$middleware->addMiddleware(AuthorizationGuardMiddleware::class);
$middleware->addMiddleware(AuthorizationMiddleware::class);
$middleware->addMiddleware(EnforceApiResponseMiddleware::class);
$middleware->addMiddleware(ErrorMiddleware::class);
$middleware->addMiddleware(SessionDataMiddleware::class);
$middleware->addMiddleware(UserMiddleware::class);
$middleware->addMiddleware(XsrfProtectionMiddleware::class);

$middleware->addMiddleware(Api\Account\ChangeAttributesAction::class);
$middleware->addMiddleware(Api\Account\ChangeEmailAction::class);
$middleware->addMiddleware(Api\Account\ChangeLocaleAction::class);
$middleware->addMiddleware(Api\Account\ChangePasswordAction::class);
$middleware->addMiddleware(Api\Account\ConfigAction::class);

$middleware->addMiddleware(Api\Auth\LoginAction::class);
$middleware->addMiddleware(Api\Auth\LogoutAction::class);
$middleware->addMiddleware(Api\Auth\UserAction::class);
$middleware->addMiddleware(Api\Config\ConfigAction::class);
$middleware->addMiddleware(Api\Dashboard\IndexAction::class);
$middleware->addMiddleware(Api\Resource\Widgets\WidgetsAction::class);
$middleware->addMiddleware(Api\Resource\CreateAction::class);
$middleware->addMiddleware(Api\Resource\DefaultValueAction::class);
$middleware->addMiddleware(Api\Resource\DeleteAction::class);
$middleware->addMiddleware(Api\Resource\DetailAction::class);
$middleware->addMiddleware(Api\Resource\IndexAction::class);
$middleware->addMiddleware(Api\Resource\UpdateAction::class);
$middleware->addMiddleware(Api\Session\SessionAction::class);
$middleware->addMiddleware(Api\User\ConfigAction::class);
$middleware->addMiddleware(Api\User\CreateAction::class);
$middleware->addMiddleware(Api\User\DeleteAction::class);
$middleware->addMiddleware(Api\User\DetailAction::class);
$middleware->addMiddleware(Api\User\IndexAction::class);
$middleware->addMiddleware(Api\User\UpdateAction::class);
$middleware->addMiddleware(Auth\LoginAction::class);
$middleware->addMiddleware(Auth\GoogleAuthStartAction::class);
$middleware->addMiddleware(Auth\GoogleAuthCallbackAction::class);
$middleware->addMiddleware(Auth\LogoutAction::class);
$middleware->addMiddleware(Auth\LostPasswordAction::class);
$middleware->addMiddleware(Auth\RecoverPasswordAction::class);
$middleware->addMiddleware(IndexAction::class);
$middleware->addMiddleware(StaticAction::class);
