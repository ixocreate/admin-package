<?php

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Auth;

use Firebase\JWT\JWT;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Template\TemplateResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LostPasswordAction implements MiddlewareInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {


        if ($request->getMethod() == 'POST') {
            $data = $request->getParsedBody();

            if (empty($data['email'])) {

            }

            $token = JWT::encode(
                [
                    'iat' => \time(),
                    'jti' => \base64_encode(\random_bytes(32)),
                    'iss' => $request->getUri()->getHost(),
                    'exp' => \time() + 60 * 10,
                    'sub' => 'lost-password',
                    'email' => $data['email'],
                ],
                'secret_key',
                'HS512'
            );

            /** @var User $user */
            $user = $this->userRepository->findOneBy(['email' => $data['email']]);
            if (empty($user)) {

            }



        }

        return $response;




        return new TemplateResponse('admin::auth/lost-password');
    }

    private function getUser($email)
    {

    }
}
