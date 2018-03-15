<?php
namespace KiwiSuite\Admin\Resource;

use KiwiSuite\Admin\Action\Api\Crud\DetailAction;
use KiwiSuite\Admin\Action\Api\Crud\IndexAction;
use KiwiSuite\Admin\Action\Handler\HandlerAction;
use KiwiSuite\Admin\Middleware\Api\AuthorizationGuardMiddleware;
use KiwiSuite\Admin\Pipe\PipeConfigurator;
use KiwiSuite\ApplicationHttp\Pipe\GroupPipeConfigurator;
use KiwiSuite\ApplicationHttp\Pipe\RouteConfigurator;

final class RoutingSetup
{
    public function setup(PipeConfigurator $pipeConfigurator, ResourceMapping $resourceMapping): void
    {
        $resourceMapping = $resourceMapping->getMapping();

        $pipeConfigurator->segment('/api', function(\KiwiSuite\ApplicationHttp\Pipe\PipeConfigurator $pipeConfigurator) use ($resourceMapping){

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
                            $routeConfigurator->addOption(ResourceInterface::class, $resource);
                        }
                    );

                    $groupPipeConfigurator->post(
                        '/resource/' . $resourceName,
                        HandlerAction::class,
                        'admin.api.' . $resourceName . '.create',
                        function (RouteConfigurator $routeConfigurator) use ($resource){
                            $routeConfigurator->addOption(ResourceInterface::class, $resource);
                        }
                    );

                    $groupPipeConfigurator->delete(
                        '/resource/' . $resourceName . '/{id}',
                        HandlerAction::class,
                        'admin.api.' . $resourceName . '.delete',
                        function (RouteConfigurator $routeConfigurator) use ($resource){
                            $routeConfigurator->addOption(ResourceInterface::class, $resource);
                        }
                    );
                }
            });
        });
    }
}
