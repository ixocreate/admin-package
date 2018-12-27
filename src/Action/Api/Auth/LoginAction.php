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

namespace Ixocreate\Admin\Action\Api\Auth;

use Ixocreate\Admin\Entity\SessionData;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Response\ApiErrorResponse;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Ixocreate\Admin\Session\SessionCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

final class LoginAction implements MiddlewareInterface
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * LoginAction constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @throws \Exception
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        if (empty($data['email']) || empty($data['password'])) {
            return new ApiErrorResponse("invalid_credentials", []);
        }

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if (empty($user)) {
            return new ApiErrorResponse("invalid_credentials", []);
        }

        if (!\password_verify($data['password'], $user->password())) {
            return new ApiErrorResponse("invalid_credentials", []);
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
