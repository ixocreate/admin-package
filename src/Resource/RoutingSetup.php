<?php
namespace KiwiSuite\Admin\Resource;

use KiwiSuite\Admin\Action\Api\Crud\DetailAction;
use KiwiSuite\Admin\Action\Api\Crud\IndexAction;
use KiwiSuite\Admin\Action\Handler\HandlerAction;
use KiwiSuite\Admin\Message\Crud\CreateMessage;
use KiwiSuite\Admin\Message\Crud\DeleteMessage;
use KiwiSuite\Admin\Message\Crud\UpdateMessage;
use KiwiSuite\Admin\Middleware\Api\AuthorizationGuardMiddleware;
use KiwiSuite\Admin\Middleware\Api\EnforceApiResponseMiddleware;
use KiwiSuite\Admin\Middleware\Api\ErrorMiddleware;
use KiwiSuite\Admin\Middleware\Api\SessionDataMiddleware;
use KiwiSuite\Admin\Middleware\Api\XsrfProtectionMiddleware;
use KiwiSuite\ApplicationHttp\Pipe\GroupPipeConfigurator;
use KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator;
use KiwiSuite\ApplicationHttp\Pipe\RouteConfigurator;
use KiwiSuite\CommandBus\Message\MessageInterface;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

final class RoutingSetup
{
    public function setup(PipeConfigurator $pipeConfigurator, ResourceServiceManagerConfig $resourceServiceManagerConfig): void
    {
        $resourceMapping = $resourceServiceManagerConfig->getResourceMapping();

        $pipeConfigurator->segment('/api', function(PipeConfigurator $pipeConfigurator) use ($resourceMapping){
            $pipeConfigurator->pipe(EnforceApiResponseMiddleware::class);
            $pipeConfigurator->pipe(ErrorMiddleware::class);
            $pipeConfigurator->pipe(SessionDataMiddleware::class);
            $pipeConfigurator->pipe(XsrfProtectionMiddleware::class);
            $pipeConfigurator->pipe(BodyParamsMiddleware::class);


            //Authorized routes
            $pipeConfigurator->group(function (GroupPipeConfigurator $groupPipeConfigurator) use ($resourceMapping){
                $groupPipeConfigurator->before(AuthorizationGuardMiddleware::class);

                foreach ($resourceMapping as $resourceName => $resource) {
                    $groupPipeConfigurator->get(
                        '/resource/' . $resourceName,
                        IndexAction::class,
                        'admin.api.' . $resourceName . '.index',
                        function (RouteConfigurator $routeConfigurator) use ($resource){
                            $routeConfigurator->addOption(ResourceInterface::class, $resource);
                        }
                    );

                    $groupPipeConfigurator->get(
                        '/resource/' . $resourceName . '/{id}',
                        DetailAction::class,
                        'admin.api.' . $resourceName . '.detail',
                        function (RouteConfigurator $routeConfigurator) use ($resource){
                            $routeConfigurator->addOption(ResourceInterface::class, $resource);
                        }
                    );

                    $groupPipeConfigurator->patch(
                        '/resource/' . $resourceName . '/{id}',
                        HandlerAction::class,
                        'admin.api.' . $resourceName . '.update',
                        function (RouteConfigurator $routeConfigurator) use ($resource){
                            $routeConfigurator->addOption(MessageInterface::class, UpdateMessage::class);
                            $routeConfigurator->addOption(ResourceInterface::class, $resource);
                        }
                    );

                    $groupPipeConfigurator->post(
                        '/resource/' . $resourceName,
                        HandlerAction::class,
                        'admin.api.' . $resourceName . '.create',
                        function (RouteConfigurator $routeConfigurator) use ($resource){
                            $routeConfigurator->addOption(MessageInterface::class, CreateMessage::class);
                            $routeConfigurator->addOption(ResourceInterface::class, $resource);
                        }
                    );

                    $groupPipeConfigurator->delete(
                        '/resource/' . $resourceName . '/{id}',
                        HandlerAction::class,
                        'admin.api.' . $resourceName . '.delete',
                        function (RouteConfigurator $routeConfigurator) use ($resource){
                            $routeConfigurator->addOption(MessageInterface::class, DeleteMessage::class);
                            $routeConfigurator->addOption(ResourceInterface::class, $resource);
                        }
                    );
                }
            });
        });
    }
}
