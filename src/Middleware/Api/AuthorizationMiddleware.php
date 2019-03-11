<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Middleware\Api;

use Ixocreate\Admin\Config\AdminProjectConfig;
use Ixocreate\Admin\Entity\SessionData;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Permission\Permission;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Response\ApiErrorResponse;
use Ixocreate\CommonTypes\Entity\UuidType;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

final class AuthorizationMiddleware implements MiddlewareInterface
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

        if (!empty($user->lastActivityAt())) {
            /** @var \DateTimeImmutable $dateTime */
            $dateTime = $user->lastActivityAt()->value();

            if ($dateTime->getTimestamp() < time() - $this->adminProjectConfig->sessionTimeout()) {
                return $handler->handle($request);
            }
        }

        return $handler->handle($request
            ->withAttribute(User::class, $user)
        );
    }
}
