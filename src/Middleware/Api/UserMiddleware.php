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

final class UserMiddleware implements MiddlewareInterface
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
            return $handler->handle($request);
        }

        if (!($sessionData->userId() instanceof UuidType)) {
            return $handler->handle($request);
        }

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['id' => $sessionData->userId()]);
        if (empty($user)) {
            return $handler->handle($request);
        }

        $permission = new Permission($user);

        return $handler->handle($request
            ->withAttribute(User::class, $user)
            ->withAttribute(Permission::class, $permission)
        );
    }

    private function createInvalidSidResponse(): ApiErrorResponse
    {
        return new ApiErrorResponse('session_invalid', [], 401);
    }
}
