<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Api\Resource;

use Doctrine\Common\Collections\Criteria;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Permission\Permission;
use Ixocreate\Admin\Resource\Action\IndexActionAwareInterface;
use Ixocreate\Admin\Resource\Schema\ListSchemaAwareInterface;
use Ixocreate\Admin\Response\ApiErrorResponse;
use Ixocreate\Admin\Response\ApiListResponse;
use Ixocreate\Application\Http\Middleware\MiddlewareSubManager;
use Ixocreate\Database\EntityManager\Factory\EntityManagerSubManager;
use Ixocreate\Database\Repository\Factory\RepositorySubManager;
use Ixocreate\Database\Repository\RepositoryInterface;
use Ixocreate\Entity\EntityInterface;
use Ixocreate\Resource\ResourceInterface;
use Ixocreate\Resource\ResourceSubManager;
use Ixocreate\Schema\ListElement\ListElementInterface;
use Ixocreate\Schema\ListSchema\ListSchemaInterface;
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
        /** @var ResourceInterface $resource */
        $resource = $this->resourceSubManager->get($request->getAttribute('resource'));

        /** @var Permission $permission */
        $permission = $request->getAttribute(Permission::class);
        if (!$permission->can('resource.' . $resource->serviceName() . '.index')) {
            return new ApiErrorResponse('forbidden', [], 403);
        }

        $middlewarePipe = new MiddlewarePipe();

        if ($resource instanceof IndexActionAwareInterface) {
            /** @var MiddlewareInterface $action */
            $action = $this->middlewareSubManager->get($resource->indexAction($request->getAttribute(User::class)));
            $middlewarePipe->pipe($action);
        }

        $middlewarePipe->pipe(new CallableMiddlewareDecorator(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($resource) {
            return $this->handleRequest($resource, $request, $handler);
        }));

        return $middlewarePipe->process($request, $handler);
    }

    private function handleRequest(ResourceInterface $resource, ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        if (!($resource instanceof ListSchemaAwareInterface)) {
            return new ApiListResponse($resource, [], ['count' => 0]);
        }

        /** @var ListSchemaInterface $listSchema */
        $listSchema = $resource->listSchema($request->getAttribute(User::class));

        /** @var RepositoryInterface $repository */
        $repository = $this->repositorySubManager->get($resource->repository());

        $queryParams = $request->getQueryParams();
        $items = [];

        /**
         * Apply preselected item values filter from query string
         * ?preselectFilter=id&preselectFilterValues[0]=id1&preselectFilterValues[1]=id2
         */
        if (($queryParams['preselectFilter'] ?? null) && !empty($queryParams['preselectFilterValues'] ?? null)) {
            $criteria = new Criteria();
            $preselectFilterValues = $queryParams['preselectFilterValues'];
            if (!\is_array($preselectFilterValues)) {
                $preselectFilterValues = [$preselectFilterValues];
            }
            $criteria->where(Criteria::expr()->in($queryParams['preselectFilter'], $preselectFilterValues));
            $result = $repository->matching($criteria);
            foreach ($result as $entity) {
                $items[] = $entity->toPublicArray();
            }
        }

        /**
         * Apply limit, offset, filters and sorts from query string
         * ?sort[column1]=ASC&sort[column2]=DESC&filter[column1]=test&filter[column2]=foobar
         */
        $criteria = new Criteria();

        /**
         * apply soft deletes
         * TODO: make this overridable so deleted items can be listed as well
         */
        if (\method_exists($repository->getEntityName(), 'deletedAt')) {
            $criteria->andWhere(Criteria::expr()->isNull('deletedAt'));
        }

        $sorting = [];
        $filterExpressions = [];
        foreach ($queryParams as $key => $value) {
            /**
             * TODO: simplify elseif chain? $key === 'sort' and $key === 'filter'? legacy code depending on it?
             */
            if ($key === 'orderBy') {
                $sorting[$value] = $queryParams['orderDirection'] ?? 'asc';
            } elseif ($key === 'orderDirection') {
                // see orderBy
            } elseif (\mb_substr($key, 0, 4) === 'sort') {
                foreach ($value as $sortName => $sortValue) {
                    if (!$listSchema->has($sortName)) {
                        continue;
                    }
                    $sorting[$sortName] = $sortValue;
                }
            } elseif (\mb_substr($key, 0, 6) === 'filter') {
                foreach ($value as $filterName => $filterValue) {
                    if (!\is_string($filterValue)) {
                        continue;
                    }
                    if (!$listSchema->has($filterName)) {
                        continue;
                    }
                    /** @var ListElementInterface $element */
                    $element = $listSchema->elements()[$filterName];
                    if (!$element->searchable()) {
                        continue;
                    }
                    $filterExpressions[] = $criteria::expr()->contains($element->name(), $filterValue);
                }
            } elseif ($key === 'search' && \is_string($value)) {
                foreach ($listSchema->elements() as $element) {
                    if (!$element->searchable()) {
                        continue;
                    }
                    $filterExpressions[] = $criteria::expr()->contains($element->name(), $value);
                }
                continue;
            } elseif ($key === 'offset') {
                $value = (int)$value;
                if (!empty($value)) {
                    $criteria->setFirstResult($value);
                }
                continue;
            } elseif ($key === 'limit') {
                $value = (int)$value;
                if (!empty($value)) {
                    $criteria->setMaxResults(\min($value, 500));
                }
                continue;
            }
        }

        /**
         * apply collected filters
         */
        if (!empty($filterExpressions)) {
            $criteria->andWhere(Criteria::expr()->andX(...$filterExpressions));
        }

        /**
         * apply collected sorts
         */
        if (empty($sorting) && !empty($listSchema->defaultSorting())) {
            $criteria->orderBy([$listSchema->defaultSorting()['sorting'] => $listSchema->defaultSorting()['direction']]);
        } elseif (!empty($sorting)) {
            $criteria->orderBy($sorting);
        }
        $result = $repository->matching($criteria);

        /**
         * TODO: Collection
         */
        /** @var EntityInterface $entity */
        foreach ($result as $entity) {
            $items[] = $entity->toPublicArray();
        }
        $count = $repository->count($criteria);

        /**
         * TODO: add active constraints to meta
         * this way it is clear for the consumer which constraints were actually applied
         */
        return new ApiListResponse($resource, $items, ['count' => $count]);
    }
}
