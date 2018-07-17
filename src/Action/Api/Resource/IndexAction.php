<?php
namespace KiwiSuite\Admin\Action\Api\Resource;

use Doctrine\Common\Collections\Criteria;
use KiwiSuite\Admin\Response\ApiListResponse;
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

    public function __construct(RepositorySubManager $repositorySubManager, MiddlewareSubManager $middlewareSubManager)
    {

        $this->repositorySubManager = $repositorySubManager;
        $this->middlewareSubManager = $middlewareSubManager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var AdminAwareInterface $resource */
        $resource = $request->getAttribute(ResourceInterface::class);

        if (!empty($resource->indexAction())) {
            /** @var MiddlewareInterface $action */
            $action = $this->middlewareSubManager->get($resource->indexAction());
            return $action->process($request, $handler);
        }

        /** @var RepositoryInterface $repository */
        $repository = $this->repositorySubManager->get($resource->repository());
        $criteria = new Criteria();
        $sorting = null;

        //?sortColumn1=ASC&sortColumn2=DESC&filterColumn1=test&filterColumn2=foobar
        $queryParams = $request->getQueryParams();
        foreach ($queryParams as $key => $value) {
            if (\mb_substr($key, 0, 4) === "sort") {
                //filter
                continue;
            }
            if (\mb_substr($key, 0, 6) === "filter") {
                //filter
                continue;
            }
            if ($key === "offset") {
                $value = (int) $value;
                if (!empty($value)) {
                    $criteria->setFirstResult($value);
                }
                continue;
            }
            if ($key === "limit") {
                $value = (int) $value;
                if (!empty($value)) {
                    $criteria->setMaxResults(min($value, 500));
                }
                continue;
            }
        }

        if ($sorting === null && !empty($resource->listSchema()->defaultSorting())) {
            $criteria->orderBy([$resource->listSchema()->defaultSorting()['sorting'] => $resource->listSchema()->defaultSorting()['direction']]);
        }

        $result = $repository->matching($criteria);
        $items = [];
        //TODO Collection
        /** @var EntityInterface $entity */
        foreach ($result as $entity) {
            $items[] = $entity->toPublicArray();
        }

        $count = $repository->count([]);

        return new ApiListResponse($resource, $items, ['count' => $count]);
    }
}
