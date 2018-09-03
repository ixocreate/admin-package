<?php
declare(strict_types=1);

namespace KiwiSuite\Admin;

/** @var PipeConfigurator $pipe */
use KiwiSuite\Admin\Action\Account\ChangeEmailAction;
use KiwiSuite\Admin\Action\Account\ChangePasswordAction;
use KiwiSuite\Admin\Action\Api\Auth\LoginAction;
use KiwiSuite\Admin\Action\Api\Auth\LogoutAction;
use KiwiSuite\Admin\Action\Api\Auth\UserAction;
use KiwiSuite\Admin\Action\Api\Config\ConfigAction;
use KiwiSuite\Admin\Action\Api\Resource\CreateAction;
use KiwiSuite\Admin\Action\Api\Resource\CreateSchemaAction;
use KiwiSuite\Admin\Action\Api\Resource\DeleteAction;
use KiwiSuite\Admin\Action\Api\Resource\DetailAction;
use KiwiSuite\Admin\Action\Api\Resource\UpdateAction;
use KiwiSuite\Admin\Action\Api\Session\SessionAction;
use KiwiSuite\Admin\Action\IndexAction;
use KiwiSuite\Admin\Action\StaticAction;
use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Middleware\Api\AuthorizationGuardMiddleware;
use KiwiSuite\Admin\Middleware\Api\EnforceApiResponseMiddleware;
use KiwiSuite\Admin\Middleware\Api\ErrorMiddleware;
use KiwiSuite\Admin\Middleware\Api\MessageInjectorMiddleware;
use KiwiSuite\Admin\Middleware\Api\ResourceInjectionMiddleware;
use KiwiSuite\Admin\Middleware\Api\SessionDataMiddleware;
use KiwiSuite\Admin\Middleware\Api\UserMiddleware;
use KiwiSuite\Admin\Middleware\Api\XsrfProtectionMiddleware;
use KiwiSuite\Admin\Middleware\CookieInitializerMiddleware;
use KiwiSuite\Admin\Router\AdminRouter;
use KiwiSuite\ApplicationHttp\Pipe\GroupPipeConfigurator;
use KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

$pipe->segmentPipe(AdminConfig::class, 2000000)(function(PipeConfigurator $pipe) {
    $pipe->segment('/api')( function(PipeConfigurator $pipe) {
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


            $group->patch('/account/email', ChangeEmailAction::class, 'admin.api.account.email');

            $group->patch('/account/password', ChangePasswordAction::class, 'admin.api.account.password');

            $group->group('resource')(function(GroupPipeConfigurator $group) {
                //$group->before(ResourceInjectionMiddleware::class);

                $group->get('/resource/{resource}', \KiwiSuite\Admin\Action\Api\Resource\IndexAction::class, 'admin.api.resource.index', PHP_INT_MAX * -1);
                $group->get('/resource/{resource}/detail/{id}', DetailAction::class, 'admin.api.resource.detail', PHP_INT_MAX * -1);
                $group->get('/resource/{resource}/create', CreateSchemaAction::class, 'admin.api.resource.createDetail', PHP_INT_MAX * -1);
                $group->patch('/resource/{resource}/{id}', UpdateAction::class, 'admin.api.resource.update', PHP_INT_MAX * -1);
                $group->post('/resource/{resource}', CreateAction::class, 'admin.api.resource.create', PHP_INT_MAX * -1);
                $group->delete('/resource/{resource}/{id}', DeleteAction::class, 'admin.api.resource.delete', PHP_INT_MAX * -1);
            });

        });
    });

    $pipe->setRouter(AdminRouter::class);
    $pipe->group('admin.root')(function(GroupPipeConfigurator $group) {
        $group->before(CookieInitializerMiddleware::class);
        $group->get('/session', SessionAction::class, "admin.session");
        $group->get('/static/{file:.*}', StaticAction::class, "admin.static");
        $group->get('[/{any:.*}]', IndexAction::class, "admin.index", -1 * PHP_INT_MAX);
    });
});


