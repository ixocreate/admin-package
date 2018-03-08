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
     * AuthorizationGuardMiddleware constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $sessionData = $request->getAttribute(SessionData::class);
        if (!($sessionData instanceof SessionData)) {
            return $this->createNotAuthorizedResponse();
        }

        if (!($sessionData->userId() instanceof UuidType)) {
            return $this->createNotAuthorizedResponse();
        }

        $user = $this->userRepository->findOneBy(['id' => $sessionData->userId()]);
        if (empty($user)) {
            return $this->createNotAuthorizedResponse();
        }

        $request = $request->withAttribute(User::class, $user);

        $permission = new Permission($user);

        $request = $request->withAttribute(Permission::class, $permission);

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
