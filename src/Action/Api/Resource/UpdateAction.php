<?php
namespace KiwiSuite\Admin\Action\Api\Resource;

use Doctrine\Common\Collections\Criteria;
use KiwiSuite\Admin\Response\ApiDetailResponse;
use KiwiSuite\Admin\Response\ApiListResponse;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\ApplicationHttp\Middleware\MiddlewareSubManager;
use KiwiSuite\Contract\Resource\AdminAwareInterface;
use KiwiSuite\Contract\Resource\ResourceInterface;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use KiwiSuite\Entity\Entity\EntityInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

final class UpdateAction implements MiddlewareInterface
{
    /**
     * @var RepositorySubManager
     */
    private $repositorySubManager;
    /**
     * @var MiddlewareSubManager
     */
    private $middlewareSubManager;

    public function __construct(RepositorySubManager $repositorySubManager, MiddlewareSubManager $middlewareSubManager)
    {

        $this->repositorySubManager = $repositorySubManager;
        $this->middlewareSubManager = $middlewareSubManager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AdminAwareInterface $resource */
        $resource = $request->getAttribute(ResourceInterface::class);

        if (!empty($resource->updateAction())) {
            /** @var MiddlewareInterface $action */
            $action = $this->middlewareSubManager->get($resource->updateAction());
            return $action->process($request, $handler);
        }

        /** @var RepositoryInterface $repository */
        $repository = $this->repositorySubManager->get($resource->repository());

        /** @var EntityInterface $entity */
        $entity = $repository->find($request->getAttribute("id"));

        $data = $request->getParsedBody();
        foreach ($data as $name => $value) {
            $entity = $entity->with($name, $value);
        }
        if ($entity::getDefinitions()->has('updatedAt')) {
            $entity = $entity->with('updatedAt', new \DateTime());
        }
        $repository->save($entity);

        return new ApiSuccessResponse();
    }
}
