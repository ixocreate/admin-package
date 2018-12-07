<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Action\Api\Resource;

use Doctrine\Common\Collections\Criteria;
use KiwiSuite\Admin\Response\ApiListResponse;
use KiwiSuite\ApplicationHttp\Middleware\MiddlewareSubManager;
use KiwiSuite\Contract\Resource\AdminAwareInterface;
use KiwiSuite\Database\EntityManager\Factory\EntityManagerSubManager;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Resource\SubManager\ResourceSubManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Stratigility\Middleware\CallableMiddlewareDecorator;
use Zend\Stratigility\MiddlewarePipe;

final class IndexAction implements MiddlewareInterface
{
    /**
     * @var RepositorySubManager
     */
    private $repositorySubManager;
    /**
     * @var MiddlewareSubManager
     */
    private $middlewareSubManager;
    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;
    /**
     * @var EntityManagerSubManager
     */
    private $entitySubManager;

    public function __construct(
        RepositorySubManager $repositorySubManager,
        MiddlewareSubManager $middlewareSubManager,
        ResourceSubManager $resourceSubManager,
        EntityManagerSubManager $entitySubManager
    ) {
        $this->repositorySubManager = $repositorySubManager;
        $this->middlewareSubManager = $middlewareSubManager;
        $this->resourceSubManager = $resourceSubManager;
        $this->entitySubManager = $entitySubManager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AdminAwareInterface $resource */
        $resource = $this->resourceSubManager->get($request->getAttribute("resource"));

        $middlewarePipe = new MiddlewarePipe();

        if (!empty($resource->indexAction())) {
            /** @var MiddlewareInterface $action */
            $action = $this->middlewareSubManager->get($resource->indexAction());
            $middlewarePipe->pipe($action);
        }

        $middlewarePipe->pipe(new CallableMiddlewareDecorator(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($resource) {
            return $this->handleRequest($resource, $request, $handler);
        }));

        return $middlewarePipe->process($request, $handler);
    }

    private function handleRequest(AdminAwareInterface $resource, ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $listSchema = $resource->listSchema();

        /** @var RepositoryInterface $repository */
        $repository = $this->repositorySubManager->get($resource->repository());
        $criteria = new Criteria();
        $sorting = null;

        //?sort[column1]=ASC&sort[column2]=DESC&filter[column1]=test&filter[column2]=foobar
        $queryParams = $request->getQueryParams();
        foreach ($queryParams as $key => $value) {
            if (\mb_substr($key, 0, 4) === "sort") {
                $sorting = [];
                foreach ($value as $sortName => $sortValue) {
                    if (!$listSchema->has($sortName)) {
                        continue;
                    }
                    $sorting[$sortName] = $sortValue;
                }
            } elseif ($key === "search" && \is_string($value)) {
                foreach ($listSchema->elements() as $element) {
                    if (!$element->searchable()) {
                        continue;
                    }
                    $criteria->orWhere(Criteria::expr()->contains($element->name(), $value));
                }
                continue;
            } elseif ($key === "offset") {
                $value = (int) $value;
                if (!empty($value)) {
                    $criteria->setFirstResult($value);
                }
                continue;
            } elseif ($key === "limit") {
                $value = (int) $value;
                if (!empty($value)) {
                    $criteria->setMaxResults(\min($value, 500));
                }
                continue;
            }
        }

        if (empty($sorting) && !empty($resource->listSchema()->defaultSorting())) {
            $criteria->orderBy([$resource->listSchema()->defaultSorting()['sorting'] => $resource->listSchema()->defaultSorting()['direction']]);
        } elseif (!empty($sorting)) {
            $criteria->orderBy($sorting);
        }

        $result = $repository->matching($criteria);
        $items = [];
        //TODO Collection
        /** @var EntityInterface $entity */
        foreach ($result as $entity) {
            if (\method_exists($entity,'deletedAt') && $entity->deletedAt() !== null) {
                continue;
            }

            $items[] = $entity->toPublicArray();
        }

        $count = $repository->count($criteria);

        if (\method_exists($repository->getEntityName(), 'deletedAt')) {
            $count = $repository->count(['deletedAt' => null]);
        }

        return new ApiListResponse($resource, $items, ['count' => $count]);
    }
}
