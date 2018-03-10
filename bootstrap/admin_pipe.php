<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var \KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator $adminPipeConfigurator */

use KiwiSuite\Admin\Action\Api\Auth\LoginAction;
use KiwiSuite\Admin\Action\Api\Auth\LogoutAction;
use KiwiSuite\Admin\Action\Api\Auth\PasswordEmailAction;
use KiwiSuite\Admin\Action\Api\Auth\PasswordResetAction;
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
use KiwiSuite\Admin\Middleware\CorsMiddleware;
use KiwiSuite\ApplicationHttp\Pipe\GroupPipeConfigurator;
use KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator;
use KiwiSuite\ApplicationHttp\Pipe\RouteConfigurator;
use KiwiSuite\CommandBus\Message\MessageInterface;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;
use Zend\Expressive\Middleware\ImplicitHeadMiddleware;
use Zend\Expressive\Middleware\ImplicitOptionsMiddleware;

$adminPipeConfigurator->segment('/api', function(PipeConfigurator $pipeConfigurator) {
    $pipeConfigurator->pipe(EnforceApiResponseMiddleware::class);
    $pipeConfigurator->pipe(ErrorMiddleware::class);
    $pipeConfigurator->pipe(SessionDataMiddleware::class);
    $pipeConfigurator->pipe(XsrfProtectionMiddleware::class);
    $pipeConfigurator->pipe(BodyParamsMiddleware::class);

    $pipeConfigurator->pipe(ResourceInjectorMiddleware::class, PipeConfigurator::PRIORITY_POST_ROUTING);
    $pipeConfigurator->pipe(MessageInjectorMiddleware::class, PipeConfigurator::PRIORITY_POST_ROUTING);


    //Unauthorized routes
    $pipeConfigurator->group(function (GroupPipeConfigurator $groupPipeConfigurator) {
        $groupPipeConfigurator->get('/config', ConfigAction::class, "admin.api.config");
        $groupPipeConfigurator->post('/auth/login', LoginAction::class, "admin.api.auth.login");
    });

    //Authorized routes
    $pipeConfigurator->group(function (GroupPipeConfigurator $groupPipeConfigurator) {
        $groupPipeConfigurator->before(AuthorizationGuardMiddleware::class);

        $groupPipeConfigurator->get('/auth/user', UserAction::class, "admin.api.auth.user");
        $groupPipeConfigurator->post('/auth/logout', LogoutAction::class, "admin.api.auth.logout");

        $groupPipeConfigurator->patch(
            '/account/email',
            HandlerAction::class,
            'admin.api.account.email',
            function (RouteConfigurator $routeConfigurator) {
                $routeConfigurator->addOption(MessageInterface::class, ChangeEmailMessage::class);
            }
        );

        $groupPipeConfigurator->patch(
            '/user/email/{id}',
            HandlerAction::class,
            'admin.api.user.email',
            function (RouteConfigurator $routeConfigurator) {
                $routeConfigurator->addOption(MessageInterface::class, ChangeEmailMessage::class);
            }
        );

        $groupPipeConfigurator->patch(
            '/account/password',
            HandlerAction::class,
            'admin.api.account.password',
            function (RouteConfigurator $routeConfigurator) {
                $routeConfigurator->addOption(MessageInterface::class, ChangePasswordMessage::class);
            }
        );
    });
});

//initializer routes
$adminPipeConfigurator->group(function (GroupPipeConfigurator $groupPipeConfigurator) {
    $groupPipeConfigurator->before(CookieInitializerMiddleware::class);
    $groupPipeConfigurator->get('/session', SessionAction::class, "admin.session");
    /*$groupPipeConfigurator->get('[/{any:.*}]', IndexAction::class, "admin.admin", function (RouteConfigurator $routeConfigurator) {
        $routeConfigurator->setPriority(1 );
    });*/
});

$adminPipeConfigurator->pipe(IndexAction::class, 1);
