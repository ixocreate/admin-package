<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Package;

use Ixocreate\Application\Http\Pipe\GroupPipeConfigurator;
use Ixocreate\Application\Http\Pipe\PipeConfigurator;
use Ixocreate\Admin\Package\Action\Account\ChangeEmailAction;
use Ixocreate\Admin\Package\Action\Account\ChangeLocaleAction;
use Ixocreate\Admin\Package\Action\Account\ChangePasswordAction;
use Ixocreate\Admin\Package\Action\Api\Auth\UserAction;
use Ixocreate\Admin\Package\Action\Api\Config\ConfigAction;
use Ixocreate\Admin\Package\Action\Api\Resource\CreateAction;
use Ixocreate\Admin\Package\Action\Api\Resource\DefaultValueAction;
use Ixocreate\Admin\Package\Action\Api\Resource\DeleteAction;
use Ixocreate\Admin\Package\Action\Api\Resource\DetailAction;
use Ixocreate\Admin\Package\Action\Api\Resource\UpdateAction;
use Ixocreate\Admin\Package\Action\Api\Session\SessionAction;
use Ixocreate\Admin\Package\Action\Auth\LoginAction;
use Ixocreate\Admin\Package\Action\Auth\LogoutAction;
use Ixocreate\Admin\Package\Action\Auth\LostPasswordAction;
use Ixocreate\Admin\Package\Action\Auth\RecoverPasswordAction;
use Ixocreate\Admin\Package\Action\IndexAction;
use Ixocreate\Admin\Package\Action\Resource\Widgets\WidgetsAction;
use Ixocreate\Admin\Package\Action\StaticAction;
use Ixocreate\Admin\Package\Config\AdminConfig;
use Ixocreate\Admin\Package\Middleware\Api\ActivityMiddleware;
use Ixocreate\Admin\Package\Middleware\Api\AuthorizationGuardMiddleware;
use Ixocreate\Admin\Package\Middleware\Api\AuthorizationMiddleware;
use Ixocreate\Admin\Package\Middleware\Api\EnforceApiResponseMiddleware;
use Ixocreate\Admin\Package\Middleware\Api\ErrorMiddleware;
use Ixocreate\Admin\Package\Middleware\Api\SessionDataMiddleware;
use Ixocreate\Admin\Package\Middleware\Api\XsrfProtectionMiddleware;
use Ixocreate\Admin\Package\Router\AdminRouter;
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

            $group->get('/user', \Ixocreate\Admin\Package\Action\Api\User\IndexAction::class, 'admin.api.user.index');
            $group->post('/user', \Ixocreate\Admin\Package\Action\Api\User\CreateAction::class,
                'admin.api.user.create');
            $group->get('/user/{id}', \Ixocreate\Admin\Package\Action\Api\User\DetailAction::class,
                'admin.api.user.detail');
            $group->get('/user/config', \Ixocreate\Admin\Package\Action\Api\User\ConfigAction::class,
                'admin.api.user.config');
            $group->patch('/user/{id}', \Ixocreate\Admin\Package\Action\Api\User\UpdateAction::class,
                'admin.api.user.update');
            $group->delete('/user/{id}', \Ixocreate\Admin\Package\Action\Api\User\DeleteAction::class,
                'admin.api.user.delete');
            $group->post('/user/resetPassword', ChangeEmailAction::class, 'admin.api.user.resetPassword');

            $group->get('/account/config', \Ixocreate\Admin\Package\Action\Account\ConfigAction::class,
                'admin.api.account.config');
            $group->patch('/account/email', ChangeEmailAction::class, 'admin.api.account.email');
            $group->patch('/account/locale', ChangeLocaleAction::class, 'admin.api.account.locale');
            $group->patch('/account/password', ChangePasswordAction::class, 'admin.api.account.password');
            $group->patch('/account/attributes', \Ixocreate\Admin\Package\Action\Account\ChangeAttributesAction::class,
                'admin.api.account.attributes');

            $group->get('/dashboard', \Ixocreate\Admin\Package\Action\Api\Dashboard\IndexAction::class,
                'admin.api.dashboard.index');

            $group->group('resource')(function (GroupPipeConfigurator $group) {
                $group->get('/resource/{resource}', \Ixocreate\Admin\Package\Action\Api\Resource\IndexAction::class,
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
