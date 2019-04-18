<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Middleware\Api;

use Ixocreate\Admin\Package\Config\AdminProjectConfig;
use Ixocreate\Admin\Package\Entity\User;
use Ixocreate\Admin\Package\Permission\Permission;
use Ixocreate\Admin\Package\Repository\UserRepository;
use Ixocreate\Admin\Package\Response\ApiErrorResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

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
