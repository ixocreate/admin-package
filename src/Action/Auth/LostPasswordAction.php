<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Action\Auth;

use Firebase\JWT\JWT;
use Ixocreate\Admin\Package\Config\AdminConfig;
use Ixocreate\Admin\Package\Entity\User;
use Ixocreate\Admin\Package\Repository\UserRepository;
use Ixocreate\Admin\Package\Router\AdminRouter;
use Ixocreate\Template\Package\Renderer;
use Ixocreate\Template\Package\TemplateResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class LostPasswordAction implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    protected $adminConfig;

    /**
     * @var AdminRouter
     */
    private $adminRouter;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * IndexAction constructor.
     * @param AdminConfig $adminConfig
     * @param AdminRouter $adminRouter
     */
    public function __construct(AdminConfig $adminConfig, AdminRouter $adminRouter, UserRepository $userRepository, Renderer $renderer)
    {
        $this->adminConfig = $adminConfig;
        $this->adminRouter = $adminRouter;
        $this->userRepository = $userRepository;
        $this->renderer = $renderer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $csrf = '';

        if ($request->getMethod() == 'POST') {
            $data = $request->getParsedBody();

            if (empty($data['email'])) {
            }

            $lifetime = 60 * 30;

            $token = JWT::encode(
                [
                    'iat' => \time(),
                    'jti' => \base64_encode(\random_bytes(32)),
                    'iss' => $request->getUri()->getHost(),
                    'exp' => \time() + $lifetime,
                    'sub' => 'lost-password',
                    'email' => $data['email'],
                ],
                $this->adminConfig->secret(),
                'HS512'
            );

            /** @var User $user */
            $user = $this->userRepository->findOneBy(['email' => $data['email']]);
            if (!empty($user)) {
                $this->sendEmail($user, $token, \round($lifetime / 60));
            } else {
                \usleep(\mt_rand(100, 500));
            }

            $csrf = $token;
        }

        $templateData = [

        ];

        return new TemplateResponse('admin::auth/lost-password', ['csrf' => $csrf]);
    }

    private function sendEmail(User $user, $token, $minutes)
    {
        // Create the Transport
        $transport = new \Swift_SendmailTransport('/Users/befler/develop/sendmail -t');
        //$transport = new \Swift_SendmailTransport('/usr/sbin/sendmail -t');

        $template = 'email::user/password-reset';

        $body = $this->renderer->render($template, [
            'user' => $user,
            'recover_url' => $this->adminRouter->generateUri('admin.recover-password') . '?token=' . $token,
            'minutes' => $minutes,
        ]);

        $message = new \Swift_Message('Forgotten Password Reset', $body, 'text/html');
        $message->addTo($user->email()->value());
        $message->setFrom('admin@ixolit.com');

        $mailer = new \Swift_Mailer($transport);
        $result = $mailer->send($message);
    }

    private function getUser($email)
    {
    }
}
