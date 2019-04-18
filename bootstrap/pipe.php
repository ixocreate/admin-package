<?php
declare(strict_types=1);

namespace Ixocreate\Package\Admin;

use Ixocreate\Application\Http\Pipe\GroupPipeConfigurator;
use Ixocreate\Application\Http\Pipe\PipeConfigurator;
use Ixocreate\Package\Admin\Action\Account\ChangeEmailAction;
use Ixocreate\Package\Admin\Action\Account\ChangeLocaleAction;
use Ixocreate\Package\Admin\Action\Account\ChangePasswordAction;
use Ixocreate\Package\Admin\Action\Api\Auth\UserAction;
use Ixocreate\Package\Admin\Action\Api\Config\ConfigAction;
use Ixocreate\Package\Admin\Action\Api\Resource\CreateAction;
use Ixocreate\Package\Admin\Action\Api\Resource\DefaultValueAction;
use Ixocreate\Package\Admin\Action\Api\Resource\DeleteAction;
use Ixocreate\Package\Admin\Action\Api\Resource\DetailAction;
use Ixocreate\Package\Admin\Action\Api\Resource\UpdateAction;
use Ixocreate\Package\Admin\Action\Api\Session\SessionAction;
use Ixocreate\Package\Admin\Action\Auth\LoginAction;
use Ixocreate\Package\Admin\Action\Auth\LogoutAction;
use Ixocreate\Package\Admin\Action\Auth\LostPasswordAction;
use Ixocreate\Package\Admin\Action\Auth\RecoverPasswordAction;
use Ixocreate\Package\Admin\Action\IndexAction;
use Ixocreate\Package\Admin\Action\Resource\Widgets\WidgetsAction;
use Ixocreate\Package\Admin\Action\StaticAction;
use Ixocreate\Package\Admin\Config\AdminConfig;
use Ixocreate\Package\Admin\Middleware\Api\ActivityMiddleware;
use Ixocreate\Package\Admin\Middleware\Api\AuthorizationGuardMiddleware;
use Ixocreate\Package\Admin\Middleware\Api\AuthorizationMiddleware;
use Ixocreate\Package\Admin\Middleware\Api\EnforceApiResponseMiddleware;
use Ixocreate\Package\Admin\Middleware\Api\ErrorMiddleware;
use Ixocreate\Package\Admin\Middleware\Api\SessionDataMiddleware;
use Ixocreate\Package\Admin\Middleware\Api\XsrfProtectionMiddleware;
use Ixocreate\Package\Admin\Router\AdminRouter;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

/** @var PipeConfigurator $pipe */

$pipe->segmentPipe(AdminConfig::class, 2000000)(function (PipeConfigurator $pipe) {

    $pipe->segment('/api')(function (PipeConfigurator $pipe) {

        $pipe->pipe(EnforceApiResponseMiddleware::class);
        $pipe->pipe(ErrorMiddleware::class);
        $pipe->pipe(SessionDataMiddleware::class);
        $pipe->pipe(XsrfProtectionMiddleware::class);
        $pipe->pipe(BodyParamsMiddleware::class);
        $pipe->pipe(AuthorizationMiddleware::class);

        $pipe->setRouter(AdminRouter::class);
        $pipe->group("admin.authorized")(function (GroupPipeConfigurator $group) {
            $group->before(AuthorizationGuardMiddleware::class);
            $group->before(ActivityMiddleware::class);

            $group->get('/config', ConfigAction::class, "admin.api.config");

            $group->get('/auth/user', UserAction::class, "admin.api.auth.user");

            $group->get('/user', \Ixocreate\Package\Admin\Action\Api\User\IndexAction::class, 'admin.api.user.index');
            $group->post('/user', \Ixocreate\Package\Admin\Action\Api\User\CreateAction::class,
                'admin.api.user.create');
            $group->get('/user/{id}', \Ixocreate\Package\Admin\Action\Api\User\DetailAction::class,
                'admin.api.user.detail');
            $group->get('/user/config', \Ixocreate\Package\Admin\Action\Api\User\ConfigAction::class,
                'admin.api.user.config');
            $group->patch('/user/{id}', \Ixocreate\Package\Admin\Action\Api\User\UpdateAction::class,
                'admin.api.user.update');
            $group->delete('/user/{id}', \Ixocreate\Package\Admin\Action\Api\User\DeleteAction::class,
                'admin.api.user.delete');
            $group->post('/user/resetPassword', ChangeEmailAction::class, 'admin.api.user.resetPassword');

            $group->get('/account/config', \Ixocreate\Package\Admin\Action\Account\ConfigAction::class,
                'admin.api.account.config');
            $group->patch('/account/email', ChangeEmailAction::class, 'admin.api.account.email');
            $group->patch('/account/locale', ChangeLocaleAction::class, 'admin.api.account.locale');
            $group->patch('/account/password', ChangePasswordAction::class, 'admin.api.account.password');
            $group->patch('/account/attributes', \Ixocreate\Package\Admin\Action\Account\ChangeAttributesAction::class,
                'admin.api.account.attributes');

            $group->get('/dashboard', \Ixocreate\Package\Admin\Action\Api\Dashboard\IndexAction::class,
                'admin.api.dashboard.index');

            $group->group('resource')(function (GroupPipeConfigurator $group) {
                $group->get('/resource/{resource}', \Ixocreate\Package\Admin\Action\Api\Resource\IndexAction::class,
                    'admin.api.resource.index', PHP_INT_MAX * -1);
                $group->get('/resource/{resource}/detail/{id}', DetailAction::class, 'admin.api.resource.detail',
                    PHP_INT_MAX * -1);
                $group->get('/resource/{resource}/default-values', DefaultValueAction::class,
                    'admin.api.resource.defaultValue', PHP_INT_MAX * -1);
                $group->patch('/resource/{resource}/{id}', UpdateAction::class, 'admin.api.resource.update',
                    PHP_INT_MAX * -1);
                $group->post('/resource/{resource}', CreateAction::class, 'admin.api.resource.create',
                    PHP_INT_MAX * -1);
                $group->delete('/resource/{resource}/{id}', DeleteAction::class, 'admin.api.resource.delete',
                    PHP_INT_MAX * -1);
                $group->get('/resource/{resource}/widget/{position}/{type}[/{id}]', WidgetsAction::class,
                    'admin.api.resource.widgets', PHP_INT_MAX * -1);
            });
        });
    });

    $pipe->setRouter(AdminRouter::class);
    $pipe->group('admin.root')(function (GroupPipeConfigurator $group) {
        $group->before(SessionDataMiddleware::class);
        $group->before(AuthorizationMiddleware::class);

        $group->get('[/]', IndexAction::class, "admin.index", -1 * PHP_INT_MAX);

        $group->get('/session', SessionAction::class, "admin.session");

        $group->get('/static/{file:.*}', StaticAction::class, "admin.static");

        $group->any('/login', LoginAction::class, "admin.login");
        $group->any('/logout', LogoutAction::class, "admin.logout");
        $group->any('/lost-password', LostPasswordAction::class, "admin.lost-password");
        $group->any('/recover-password', RecoverPasswordAction::class, "admin.recover-password");
    });
});
