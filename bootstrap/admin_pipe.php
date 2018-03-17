<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\Admin\Pipe\PipeConfigurator $pipe */

use KiwiSuite\Admin\Action\Api\Auth\LoginAction;
use KiwiSuite\Admin\Action\Api\Auth\LogoutAction;
use KiwiSuite\Admin\Action\Api\Auth\UserAction;
use KiwiSuite\Admin\Action\Api\Config\ConfigAction;
use KiwiSuite\Admin\Action\Api\Session\SessionAction;
use KiwiSuite\Admin\Action\Handler\HandlerAction;
use KiwiSuite\Admin\Action\IndexAction;
use KiwiSuite\Admin\Message\ChangeEmailMessage;
use KiwiSuite\Admin\Message\ChangePasswordMessage;
use KiwiSuite\Admin\Middleware\Api\AuthorizationGuardMiddleware;
use KiwiSuite\Admin\Middleware\Api\EnforceApiResponseMiddleware;
use KiwiSuite\Admin\Middleware\Api\ErrorMiddleware;
use KiwiSuite\Admin\Middleware\Api\MessageInjectorMiddleware;
use KiwiSuite\Admin\Middleware\Api\ResourceInjectorMiddleware;
use KiwiSuite\Admin\Middleware\Api\SessionDataMiddleware;
use KiwiSuite\Admin\Middleware\Api\XsrfProtectionMiddleware;
use KiwiSuite\Admin\Middleware\CookieInitializerMiddleware;
use KiwiSuite\ApplicationHttp\Pipe\GroupPipeConfigurator;
use KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator;
use KiwiSuite\ApplicationHttp\Pipe\RouteConfigurator;
use KiwiSuite\CommandBus\Message\MessageInterface;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

$pipe->segment('/api', function(PipeConfigurator $pipe) {
    $pipe->pipe(EnforceApiResponseMiddleware::class);
    $pipe->pipe(ErrorMiddleware::class);
    $pipe->pipe(SessionDataMiddleware::class);
    $pipe->pipe(XsrfProtectionMiddleware::class);
    $pipe->pipe(BodyParamsMiddleware::class);

    $pipe->pipe(ResourceInjectorMiddleware::class, PipeConfigurator::PRIORITY_POST_ROUTING);
    $pipe->pipe(MessageInjectorMiddleware::class, PipeConfigurator::PRIORITY_POST_ROUTING);


    //Unauthorized routes
    $pipe->group(function (GroupPipeConfigurator $group) {
        $group->get('/config', ConfigAction::class, "admin.api.config");
        $group->post('/auth/login', LoginAction::class, "admin.api.auth.login");
    });

    //Authorized routes
    $pipe->group(function (GroupPipeConfigurator $group) {
        $group->before(AuthorizationGuardMiddleware::class);

        $group->get('/auth/user', UserAction::class, "admin.api.auth.user");
        $group->post('/auth/logout', LogoutAction::class, "admin.api.auth.logout");

        $group->patch(
            '/account/email',
            HandlerAction::class,
            'admin.api.account.email',
            function (RouteConfigurator $route) {
                $route->addOption(MessageInterface::class, ChangeEmailMessage::class);
            }
        );

        $group->patch(
            '/user/email/{id}',
            HandlerAction::class,
            'admin.api.user.email',
            function (RouteConfigurator $route) {
                $route->addOption(MessageInterface::class, ChangeEmailMessage::class);
            }
        );

        $group->patch(
            '/account/password',
            HandlerAction::class,
            'admin.api.account.password',
            function (RouteConfigurator $route) {
                $route->addOption(MessageInterface::class, ChangePasswordMessage::class);
            }
        );
    });
});

//initializer routes
$pipe->group(function (GroupPipeConfigurator $group) {
    $group->before(CookieInitializerMiddleware::class);
    $group->get('/session', SessionAction::class, "admin.session");
    $group->get('[/{any:.*}]', IndexAction::class, "admin.index", function (RouteConfigurator $route) {
        $route->setPriority(-1 * PHP_INT_MAX );
    });
});
