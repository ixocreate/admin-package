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

use KiwiSuite\Admin\Entity\SessionData;
use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Permission\Permission;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use KiwiSuite\CommonTypes\Entity\UuidType;
use KiwiSuite\Contract\Resource\AdminAwareInterface;
use KiwiSuite\Contract\Resource\ResourceInterface;
use KiwiSuite\Resource\SubManager\ResourceSubManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

final class ResourceInjectionMiddleware implements MiddlewareInterface
{
    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;

    /**
     * AuthorizationGuardMiddleware constructor.
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
        if (!$this->resourceSubManager->has($request->getAttribute("resource"))) {
            return new ApiErrorResponse("invalid_resource");
        }
        $resource = $this->resourceSubManager->get($request->getAttribute("resource"));

        if (!($resource instanceof AdminAwareInterface)) {
            return new ApiErrorResponse("invalid_resource");
        }

        return $handler->handle($request->withAttribute(ResourceInterface::class, $resource));
    }
}
