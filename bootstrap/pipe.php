<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Admin\Action\Api\Account\ChangeEmailAction;
use Ixocreate\Admin\Action\Api\Account\ChangeLocaleAction;
use Ixocreate\Admin\Action\Api\Account\ChangePasswordAction;
use Ixocreate\Admin\Action\Api\Auth\UserAction;
use Ixocreate\Admin\Action\Api\Config\ConfigAction;
use Ixocreate\Admin\Action\Api\Resource\CreateAction;
use Ixocreate\Admin\Action\Api\Resource\DefaultValueAction;
use Ixocreate\Admin\Action\Api\Resource\DeleteAction;
use Ixocreate\Admin\Action\Api\Resource\DetailAction;
use Ixocreate\Admin\Action\Api\Resource\UpdateAction;
use Ixocreate\Admin\Action\Api\Resource\Widgets\WidgetsAction;
use Ixocreate\Admin\Action\Api\Session\SessionAction;
use Ixocreate\Admin\Action\Auth\LoginAction;
use Ixocreate\Admin\Action\Auth\LogoutAction;
use Ixocreate\Admin\Action\Auth\LostPasswordAction;
use Ixocreate\Admin\Action\Auth\RecoverPasswordAction;
use Ixocreate\Admin\Action\IndexAction;
use Ixocreate\Admin\Action\StaticAction;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Middleware\Api\ActivityMiddleware;
use Ixocreate\Admin\Middleware\Api\AuthorizationGuardMiddleware;
use Ixocreate\Admin\Middleware\Api\AuthorizationMiddleware;
use Ixocreate\Admin\Middleware\Api\EnforceApiResponseMiddleware;
use Ixocreate\Admin\Middleware\Api\ErrorMiddleware;
use Ixocreate\Admin\Middleware\Api\SessionDataMiddleware;
use Ixocreate\Admin\Middleware\Api\XsrfProtectionMiddleware;
use Ixocreate\Admin\Router\AdminRouter;
use Ixocreate\Application\Http\Pipe\GroupPipeConfigurator;
use Ixocreate\Application\Http\Pipe\PipeConfigurator;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;

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

            $group->get('/config', ConfigAction::class, "admin.api.basic.config");

            $group->get('/auth/user', UserAction::class, "admin.api.basic.auth.user");

            $group->get('/user', \Ixocreate\Admin\Action\Api\User\IndexAction::class, 'admin.api.edituser.user.index');
            $group->post('/user', \Ixocreate\Admin\Action\Api\User\CreateAction::class, 'admin.api.user.create');
            $group->get('/user/{id}', \Ixocreate\Admin\Action\Api\User\DetailAction::class, 'admin.api.user.detail');
            $group->get('/user/config', \Ixocreate\Admin\Action\Api\User\ConfigAction::class, 'admin.api.user.config');
            $group->patch('/user/{id}', \Ixocreate\Admin\Action\Api\User\UpdateAction::class, 'admin.api.user.update');
            $group->delete('/user/{id}', \Ixocreate\Admin\Action\Api\User\DeleteAction::class, 'admin.api.user.delete');
            $group->post('/user/resetPassword', ChangeEmailAction::class, 'admin.api.user.resetPassword');

            $group->get('/account/config', \Ixocreate\Admin\Action\Api\Account\ConfigAction::class, 'admin.api.basic.account.config');
            $group->patch('/account/email', ChangeEmailAction::class, 'admin.api.account.email');
            $group->patch('/account/locale', ChangeLocaleAction::class, 'admin.api.account.locale');
            $group->patch('/account/password', ChangePasswordAction::class, 'admin.api.account.password');
            $group->patch(
                '/account/attributes',
                \Ixocreate\Admin\Action\Api\Account\ChangeAttributesAction::class,
                'admin.api.account.attributes'
            );

            $group->get('/dashboard', \Ixocreate\Admin\Action\Api\Dashboard\IndexAction::class, 'admin.api.dashboard.index');

            $group->group('resource')(function (GroupPipeConfigurator $group) {
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
