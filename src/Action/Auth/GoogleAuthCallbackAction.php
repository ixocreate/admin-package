<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Action\Auth;

use Exception;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\GoogleUser;
use Ixocreate\Admin\Entity\SessionData;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Helper\GoogleAuthHelper;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Router\AdminRouter;
use Ixocreate\Admin\Session\SessionCookie;
use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Uri\ApplicationUri;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

final class GoogleAuthCallbackAction implements MiddlewareInterface {
    private AdminConfig $adminConfig;
    private AdminRouter $adminRouter;
    private ApplicationConfig $applicationConfig;
    private UserRepository $userRepository;
    private ApplicationUri $applicationUri;

    public function __construct(AdminConfig $adminConfig, AdminRouter $adminRouter, UserRepository $userRepository, ApplicationConfig $applicationConfig, ApplicationUri $applicationUri) {
        $this->adminConfig = $adminConfig;
        $this->adminRouter = $adminRouter;
        $this->userRepository = $userRepository;
        $this->applicationConfig = $applicationConfig;
        $this->applicationUri = $applicationUri;
    }

    /**
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $googleHelper = new GoogleAuthHelper($this->applicationConfig, $this->adminConfig);

        if ($googleHelper->isAllowed()) {
            try {
                if (isset($_GET['code'])) {
                    $googleUser = $googleHelper->getUser($_GET['code']);
                    if ($googleHelper->userIsAllowed($googleUser)) {
                        $user = $this->getUser($googleUser);
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
                }
            } catch (\Throwable $e) {
                // do nothing
            }
        }

        return new RedirectResponse($this->adminRouter->generateUri('admin.login'));
    }

    private function getUser(GoogleUser $googleUser): ?User {
        $email = $googleUser->getEmail();

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (empty($user)) {
            $password = \password_hash($this->random_str(25), PASSWORD_DEFAULT);

            $user = new User([
                'id' => Uuid::uuid4()->toString(),
                'email' => $email,
                'password' => $password,
                'role' => 'admin',
                'avatar' => $googleUser->getPicture(),
                'createdAt' => new \DateTimeImmutable(),
                'updatedAt' => new \DateTimeImmutable(),
                'userAttributes' => null,
                'status' => 'active',
            ]);

            $this->userRepository->save($user);
        }

        if ($user->avatar() !== $googleUser->getPicture()) {
            $this->userRepository->save($user->with('avatar', $googleUser->getPicture()));
        }

        if ($user->deletedAt()) {
            $this->userRepository->save($user->with('deletedAt', null));
        }

        return $user;
    }

    function random_str(int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$%&!,:-'): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces [] = $keyspace[\random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
