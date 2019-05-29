<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
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
            return new ApiErrorResponse('invalid_credentials', ['Empty e-mail or password!']);
        }

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if (empty($user)) {
            // dummy verify to prevent timing analysis
            \password_verify('password', '$2y$10$Uw3MyeyL91oOwt9axt4hYeP5yyY9P487G5DEUVnVsdzMdnXCmeXYS');
            return new ApiErrorResponse('invalid_credentials', ['Invalid e-mail or password!']);
        }

        if (!\password_verify($data['password'], $user->password())) {
            return new ApiErrorResponse('invalid_credentials', ['Invalid e-mail or password!']);
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
