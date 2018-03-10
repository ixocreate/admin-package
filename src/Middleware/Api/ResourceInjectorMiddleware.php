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

namespace KiwiSuite\Admin\Middleware\Api;

use KiwiSuite\Admin\Resource\ResourceInterface;
use KiwiSuite\Admin\Resource\ResourceSubManager;
use KiwiSuite\CommandBus\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

final class ResourceInjectorMiddleware implements MiddlewareInterface
{
    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;

    /**
     * ResourceInjectorMiddleware constructor.
     * @param ResourceSubManager $resourceSubManager
     */
    public function __construct(ResourceSubManager $resourceSubManager)
    {
        $this->resourceSubManager = $resourceSubManager;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);

        if (!empty($routeResult->getMatchedRoute()->getOptions()[ResourceInterface::class])) {
            /** @var ResourceInterface $resource */
            $resource = $this->resourceSubManager->get($routeResult->getMatchedRoute()->getOptions()[ResourceInterface::class]);

            $request = $request->withAttribute(ResourceInterface::class, $resource);

            if ($request->getMethod() === "POST") {
                $request = $request->withAttribute(MessageInterface::class, $resource->createMessage());
            } elseif ($request->getMethod() === "PATCH") {
                $request = $request->withAttribute(MessageInterface::class, $resource->updateMessage());
            } elseif ($request->getMethod() === "DELETE") {
                $request = $request->withAttribute(MessageInterface::class, $resource->deleteMessage());
            }
        }

        return $handler->handle($request);
    }
}
#
