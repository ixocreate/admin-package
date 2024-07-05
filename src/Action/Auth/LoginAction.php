<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Auth;

use Firebase\JWT\JWT;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\SessionData;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Router\AdminRouter;
use Ixocreate\Admin\Session\SessionCookie;
use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Template\TemplateResponse;
use Laminas\Diactoros\Response\RedirectResponse;
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
        $queryParams = $request->getQueryParams();

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

        if(method_exists($this->applicationConfig, 'getLoginTypes')) {
            $useCredentials = !empty($this->applicationConfig->getLoginTypes()['credentials']);
            $useGoogle = !empty($this->applicationConfig->getLoginTypes()['google']);
        } else {
            $useCredentials = true;
            $useGoogle = false;
        }

        $errors = [];
        if(!empty($queryParams['error'])) {
            $errors[] = $queryParams['error'];
        }
        if ($request->getMethod() == 'POST' && $useCredentials) {
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
            'useCredentials' => $useCredentials,
            'useGoogle' => $useGoogle,
            'googleStartUrl' => $this->adminRouter->generateUri('admin.google-auth-start'),
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
