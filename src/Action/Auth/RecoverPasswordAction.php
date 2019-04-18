<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Action\Auth;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Ixocreate\Admin\Package\Command\User\ChangePasswordCommand;
use Ixocreate\Admin\Package\Config\AdminConfig;
use Ixocreate\Admin\Package\Entity\User;
use Ixocreate\Admin\Package\Repository\UserRepository;
use Ixocreate\CommandBus\CommandBus;
use Ixocreate\Template\Package\TemplateResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RecoverPasswordAction implements MiddlewareInterface
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
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(AdminConfig $adminConfig, UserRepository $userRepository, CommandBus $commandBus)
    {
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
        $this->adminConfig = $adminConfig;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $errors = [];
        $validToken = false;
        $success = false;
        $params = $request->getQueryParams();

        if (empty($params['token'])) {
            $errors[] = 'Invalid Token!';
        } else {
            try {
                $tokenData = JWT::decode($params['token'], $this->adminConfig->secret(), ['HS512']);
                $validToken = true;
            } catch (ExpiredException $e) {
                $errors[] = 'Your recover link has expired!';
            } catch (\Exception $e) {
                $errors[] = 'Invalid Token!';
            }
        }

        if ($request->getMethod() == 'POST' && $validToken === true) {
            $data = $request->getParsedBody();

            /** @var User $user */
            $user = $this->userRepository->findOneBy(['email' => $tokenData['email']]);
            if (empty($user)) {
                $errors[] = 'Invalid Token!';
            }

            if (empty($errors)) {
                $result = $this->commandBus->command(
                    ChangePasswordCommand::class,
                    [
                        'user' => $user,
                        'password' => $data['password'],
                        'passwordRepeat' => $data['passwordRepeat'],
                        'skipPasswordOld' => true,
                    ]
                );

                if ($result->isSuccessful()) {
                    $success = true;
                } else {
                    $errors = $result->messages();
                }
            }
        }

        return new TemplateResponse('admin::auth/recover-password', [
            'errors' => $errors,
            'success' => $success,
            'validToken' => $validToken,
            'csrf' => '',
        ]);
    }
}
