<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Action\Api\Crud;

use App\Admin\Entity\Tag;
use Doctrine\Common\Collections\Criteria;
use KiwiSuite\Admin\Resource\ResourceInterface;
use KiwiSuite\Admin\Resource\ResourceSubManager;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\ApplicationHttp\Middleware\MiddlewareSubManager;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

final class IndexAction implements MiddlewareInterface
{
    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;
    /**
     * @var RepositorySubManager
     */
    private $repositorySubManager;

    /**
     * @var MiddlewareSubManager
     */
    private $middlewareSubManager;

    public function __construct(ResourceSubManager $resourceSubManager, RepositorySubManager $repositorySubManager, MiddlewareSubManager $middlewareSubManager)
    {
        $this->resourceSubManager = $resourceSubManager;
        $this->repositorySubManager = $repositorySubManager;
        $this->middlewareSubManager = $middlewareSubManager;
    }


    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);
        $resourceKey = $routeResult->getMatchedRoute()->getOptions()[ResourceInterface::class];

        /** @var ResourceInterface $resource */
        $resource = $this->resourceSubManager->get($resourceKey);

        if (!empty($resource->indexAction())) {
            /** @var MiddlewareInterface $action */
            $action = $this->middlewareSubManager->get($resource->indexAction());

            return $action->process($request, $handler);
        }

        /** @var RepositoryInterface $repository */
        $repository = $this->repositorySubManager->get($resource->repository());

        $criteria = new Criteria();
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
                    $criteria->setMaxResults($value);
                }

                continue;
            }
        }

        $result = $repository->matching($criteria);

        $response = [];
        //TODO Collection
        /** @var Tag $tag */
        foreach ($result as $tag) {
            $response[] = $tag->toPublicArray();
        }

        return new ApiSuccessResponse($response);
    }
}
