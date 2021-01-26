<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Middleware\Api;

use Ixocreate\Admin\Config\AdminProjectConfig;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Permission\Permission;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Response\ApiErrorResponse;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthorizationGuardMiddleware implements MiddlewareInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var AdminProjectConfig
     */
    private $adminProjectConfig;

    /**
     * AuthorizationGuardMiddleware constructor.
     * @param UserRepository $userRepository
     * @param AdminProjectConfig $adminProjectConfig
     */
    public function __construct(UserRepository $userRepository, AdminProjectConfig $adminProjectConfig)
    {
        $this->userRepository = $userRepository;
        $this->adminProjectConfig = $adminProjectConfig;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute(User::class);
        if ($user === null) {
            return new ApiErrorResponse('unauthorized', [], 401);
        }

        if (!empty($user->lastActivityAt())) {
            /** @var \DateTimeImmutable $dateTime */
            $dateTime = $user->lastActivityAt()->value();

            if ($dateTime->getTimestamp() < \time() - $this->adminProjectConfig->sessionTimeout()) {
                //return new ApiErrorResponse('unauthorized', [], 401);
            }
        }

        $permission = new Permission($user);

        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);
        if (!$permission->can($routeResult->getMatchedRouteName())) {
            return new ApiErrorResponse('forbidden', [], 403);
        }

        return $handler->handle(
            $request
            ->withAttribute(Permission::class, $permission)
        );
    }
}
