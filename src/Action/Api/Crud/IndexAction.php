<?php
namespace KiwiSuite\Admin\Action\Api\Crud;

use App\Admin\Entity\Tag;
use KiwiSuite\Admin\Resource\ResourceInterface;
use KiwiSuite\Admin\Resource\ResourceSubManager;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
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

    public function __construct(ResourceSubManager $resourceSubManager, RepositorySubManager $repositorySubManager)
    {
        $this->resourceSubManager = $resourceSubManager;
        $this->repositorySubManager = $repositorySubManager;
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

        /** @var RepositoryInterface $repository */
        $repository = $this->repositorySubManager->get($resource->repository());

        $result = $repository->findAll();

        $response = [];
        //TODO Collection
        /** @var Tag $tag */
        foreach ($result as $tag) {
            $response[] = $tag->toPublicArray();
        }

        return new ApiSuccessResponse($response);
    }
}
