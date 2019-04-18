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
use Ixocreate\Admin\Package\Entity\SessionData;
use Ixocreate\Admin\Package\Entity\User;
use Ixocreate\Admin\Package\Repository\UserRepository;
use Ixocreate\Admin\Package\Router\AdminRouter;
use Ixocreate\Admin\Package\Session\SessionCookie;
use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Template\Package\TemplateResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\RedirectResponse;

final class LoginAction implements MiddlewareInterface
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
     * @var ApplicationConfig
     */
    private $applicationConfig;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * IndexAction constructor.
     * @param AdminConfig $adminConfig
     * @param AdminRouter $adminRouter
     * @param ApplicationConfig $applicationConfig
     * @param UserRepository $userRepository
     */
    public function __construct(AdminConfig $adminConfig, AdminRouter $adminRouter, ApplicationConfig $applicationConfig, UserRepository $userRepository)
    {
        $this->adminConfig = $adminConfig;
        $this->adminRouter = $adminRouter;
        $this->userRepository = $userRepository;
        $this->applicationConfig = $applicationConfig;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $csrf = JWT::encode(
            [
                'iat' => \time(),
                'jti' => \base64_encode(\random_bytes(32)),
                'iss' => $request->getUri()->getHost(),
                'exp' => \time() + 60 * 10,
                'sub' => 'login',
            ],
            $this->adminConfig->secret(),
            'HS512'
        );

        $errors = [];
        if ($request->getMethod() == 'POST') {
            $data = $request->getParsedBody();

            try {
                $jwtData = JWT::decode($data['csrf'], $this->adminConfig->secret(), ['HS512']);
                if ($jwtData->sub !== 'login') {
                    throw new \Exception();
                }

                $user = $this->getUser($data['email'], $data['password']);
                if ($user !== null) {
                    $sessionData = new SessionData([
                        'xsrfToken' => Uuid::uuid4()->toString(),
                        'userId' => $user->id(),
                    ]);

                    $response = new RedirectResponse($this->adminRouter->generateUri('admin.index'));

                    $sessionCookie = new SessionCookie();
                    $response = $sessionCookie->createSessionCookie($request, $response, $this->adminConfig, $sessionData);
                    $response = $sessionCookie->createXsrfCookie($request, $response, $sessionData);

                    $user = $user->with('lastLoginAt', new \DateTimeImmutable());
                    $this->userRepository->flush($this->userRepository->save($user));

                    return $response;
                }

                $errors[] = 'Invalid e-mail or password!';
            } catch (\Exception $e) {
                $errors[] = 'Session expired, please try again';
                if ($this->applicationConfig->isDevelopment()) {
                    $errors[] = 'Error ' . $e->getCode() . ' occurred in ' . $e->getFile() . ':' . $e->getLine() . ' : ' . $e->getMessage();
                }
            }
        }

        $response = new TemplateResponse('admin::auth/login', [
            'csrf' => $csrf,
            'errors' => $errors,
        ]);

        return $response;
    }

    private function getUser(string $email, string $password): ?User
    {
        if (empty($email) || empty($password)) {
            return null;
        }

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (empty($user)) {
            // dummy verify to prevent timing analysis
            \password_verify('password', '$2y$10$Uw3MyeyL91oOwt9axt4hYeP5yyY9P487G5DEUVnVsdzMdnXCmeXYS');
            return null;
        }

        if (!\password_verify($password, $user->password())) {
            return null;
        }

        return $user;
    }
}
