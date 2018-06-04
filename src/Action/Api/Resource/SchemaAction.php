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

namespace KiwiSuite\Admin\Action\Api\Resource;

use KiwiSuite\Admin\Resource\ResourceInterface;
use KiwiSuite\Admin\Resource\ResourceSubManager;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Admin\Schema\SchemaInstantiator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

final class SchemaAction implements MiddlewareInterface
{
    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;
    /**
     * @var SchemaInstantiator
     */
    private $schemaInstantiator;

    public function __construct(ResourceSubManager $resourceSubManager, SchemaInstantiator $schemaInstantiator)
    {
        $this->resourceSubManager = $resourceSubManager;
        $this->schemaInstantiator = $schemaInstantiator;
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

        $schemaBuilder = $this->schemaInstantiator->createSchemaBuilder();
        $resource->schema($schemaBuilder);

        return new ApiSuccessResponse($schemaBuilder->toArray());
    }
}
