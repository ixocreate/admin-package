<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Middleware\Api;

use Ixocreate\Admin\Entity\SessionData;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Type\Entity\UuidType;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthorizationMiddleware implements MiddlewareInterface
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

        if ($user->status()->value() !== "active") {
            return $handler->handle($request);
        }

        return $handler->handle(
            $request
            ->withAttribute(User::class, $user)
        );
    }
}
