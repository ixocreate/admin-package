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

namespace KiwiSuite\Admin\Action\Api\Auth;

use KiwiSuite\Admin\Config\AdminConfig;
use KiwiSuite\Admin\Entity\SessionData;
use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Admin\Session\SessionCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

final class LoginAction implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * LoginAction constructor.
     * @param AdminConfig $adminConfig
     */
    public function __construct(AdminConfig $adminConfig, UserRepository $userRepository)
    {
        $this->adminConfig = $adminConfig;
        $this->userRepository = $userRepository;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        if (empty($data['email']) || empty($data['password'])) {
            return new ApiErrorResponse("invalid_credentials", [], 401);
        }

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if (empty($user)) {
            return new ApiErrorResponse("invalid_credentials", [], 401);
        }

        if (!password_verify($data['password'], $user->password())) {
            return new ApiErrorResponse("invalid_credentials", [], 401);
        }

        $response = new ApiSuccessResponse();

        $sessionData = new SessionData([
            'xsrfToken' => Uuid::uuid4()->toString(),
            'userId' => $user->id(),
        ]);


        $sessionCookie = new SessionCookie();
        $response = $sessionCookie->createSessionCookie($request, $response, $sessionData);
        $response = $sessionCookie->createXsrfCookie($request, $response, $sessionData);

        $user = $user->with('lastLoginAt', new \DateTimeImmutable());
        $this->userRepository->flush($this->userRepository->save($user));

        return $response;
    }
}
