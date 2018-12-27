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

namespace Ixocreate\Admin\Middleware\Api;

use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Permission\Permission;
use Ixocreate\Admin\Response\ApiErrorResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

final class AuthorizationGuardMiddleware implements MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute(User::class);
        if (!($user instanceof User)) {
            return $this->createNotAuthorizedResponse();
        }

        if ($user->status()->getValue() !== "active") {
            return $this->createNotAuthorizedResponse();
        }

        /** @var Permission $permission */
        $permission = $request->getAttribute(Permission::class);

        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);
        if (!$permission->can($routeResult->getMatchedRouteName())) {
            return new ApiErrorResponse('forbidden', [], 403);
        }

        return $handler->handle($request);
    }

    private function createNotAuthorizedResponse(): ApiErrorResponse
    {
        return new ApiErrorResponse('unauthorized', [], 401);
    }
}
