<?php
declare(strict_types=1);

namespace Ixocreate\Admin;

/** @var PipeConfigurator $pipe */

use Ixocreate\Admin\Action\Account\ChangeEmailAction;
use Ixocreate\Admin\Action\Account\ChangePasswordAction;
use Ixocreate\Admin\Action\Api\Auth\LoginAction;
use Ixocreate\Admin\Action\Api\Auth\LogoutAction;
use Ixocreate\Admin\Action\Api\Auth\UserAction;
use Ixocreate\Admin\Action\Api\Config\ConfigAction;
use Ixocreate\Admin\Action\Api\Resource\CreateAction;
use Ixocreate\Admin\Action\Api\Resource\DefaultValueAction;
use Ixocreate\Admin\Action\Api\Resource\DeleteAction;
use Ixocreate\Admin\Action\Api\Resource\DetailAction;
use Ixocreate\Admin\Action\Api\Resource\UpdateAction;
use Ixocreate\Admin\Action\Api\Session\SessionAction;
use Ixocreate\Admin\Action\IndexAction;
use Ixocreate\Admin\Action\Resource\Widgets\WidgetsAction;
use Ixocreate\Admin\Action\StaticAction;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Middleware\Api\AuthorizationGuardMiddleware;
use Ixocreate\Admin\Middleware\Api\EnforceApiResponseMiddleware;
use Ixocreate\Admin\Middleware\Api\ErrorMiddleware;
use Ixocreate\Admin\Middleware\Api\SessionDataMiddleware;
use Ixocreate\Admin\Middleware\Api\UserMiddleware;
use Ixocreate\Admin\Middleware\Api\XsrfProtectionMiddleware;
use Ixocreate\Admin\Middleware\CookieInitializerMiddleware;
use Ixocreate\Admin\Router\AdminRouter;
use Ixocreate\ApplicationHttp\Pipe\GroupPipeConfigurator;
use Ixocreate\ApplicationHttp\Pipe\PipeConfigurator;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

$pipe->segmentPipe(AdminConfig::class, 2000000)(function (PipeConfigurator $pipe) {
    $pipe->segment('/api')(function (PipeConfigurator $pipe) {
        $pipe->pipe(EnforceApiResponseMiddleware::class);
        $pipe->pipe(ErrorMiddleware::class);
        $pipe->pipe(SessionDataMiddleware::class);
        $pipe->pipe(UserMiddleware::class);
        $pipe->pipe(XsrfProtectionMiddleware::class);
        $pipe->pipe(BodyParamsMiddleware::class);

        $pipe->group("admin.unauthorized")(function (GroupPipeConfigurator $group) {
            $group->get('/config', ConfigAction::class, "admin.api.config");
            $group->post('/auth/login', LoginAction::class, "admin.api.auth.login");
        });
        $pipe->group("admin.authorized")(function (GroupPipeConfigurator $group) {
            $group->before(AuthorizationGuardMiddleware::class);

            $group->get('/auth/user', UserAction::class, "admin.api.auth.user");
            $group->post('/auth/logout', LogoutAction::class, "admin.api.auth.logout");

            $group->get('/user', \Ixocreate\Admin\Action\Api\User\IndexAction::class, 'admin.api.user.index');
            $group->post('/user', \Ixocreate\Admin\Action\Api\User\CreateAction::class, 'admin.api.user.create');
            $group->get('/user/{id}', \Ixocreate\Admin\Action\Api\User\DetailAction::class, 'admin.api.user.detail');
            $group->get('/user/config', \Ixocreate\Admin\Action\Api\User\ConfigAction::class, 'admin.api.user.config');
            $group->patch('/user/{id}', \Ixocreate\Admin\Action\Api\User\UpdateAction::class, 'admin.api.user.update');
            $group->delete('/user/{id}', \Ixocreate\Admin\Action\Api\User\DeleteAction::class, 'admin.api.user.delete');
            $group->post('/user/resetPassword', ChangeEmailAction::class, 'admin.api.user.resetPassword');

            $group->patch('/account/{id}', \Ixocreate\Admin\Action\Account\UpdateAction::class, 'admin.api.account.update');
            $group->get('/account/config', \Ixocreate\Admin\Action\Account\ConfigAction::class, 'admin.api.account.config');
            $group->patch('/account/email', ChangeEmailAction::class, 'admin.api.account.email');
            $group->patch('/account/password', ChangePasswordAction::class, 'admin.api.account.password');

            $group->get('/dashboard', \Ixocreate\Admin\Action\Api\Dashboard\IndexAction::class, 'admin.api.dashboard.index');

            $group->group('resource')(function(GroupPipeConfigurator $group) {
                $group->get('/resource/{resource}', \Ixocreate\Admin\Action\Api\Resource\IndexAction::class, 'admin.api.resource.index', PHP_INT_MAX * -1);
                $group->get('/resource/{resource}/detail/{id}', DetailAction::class, 'admin.api.resource.detail', PHP_INT_MAX * -1);
                $group->get('/resource/{resource}/default-values', DefaultValueAction::class, 'admin.api.resource.defaultValue', PHP_INT_MAX * -1);
                $group->patch('/resource/{resource}/{id}', UpdateAction::class, 'admin.api.resource.update', PHP_INT_MAX * -1);
                $group->post('/resource/{resource}', CreateAction::class, 'admin.api.resource.create', PHP_INT_MAX * -1);
                $group->delete('/resource/{resource}/{id}', DeleteAction::class, 'admin.api.resource.delete', PHP_INT_MAX * -1);
                $group->get('/resource/{resource}/widget/{position}/{type}[/{id}]', WidgetsAction::class, 'admin.api.resource.widgets', PHP_INT_MAX * -1);
            });
        });
    });

    $pipe->setRouter(AdminRouter::class);
    $pipe->group('admin.root')(function (GroupPipeConfigurator $group) {
        $group->before(CookieInitializerMiddleware::class);
        $group->get('/session', SessionAction::class, "admin.session");
        $group->get('/static/{file:.*}', StaticAction::class, "admin.static");
        $group->get('[/{any:.*}]', IndexAction::class, "admin.index", -1 * PHP_INT_MAX);
    });
});


