<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Auth;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\SessionData;
use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Admin\Router\AdminRouter;
use Ixocreate\Admin\Session\SessionCookie;
use Ixocreate\Application\ApplicationConfig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;
use Zend\Diactoros\Response\RedirectResponse;

final class LogoutAction implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    protected $adminConfig;

    /**
     * @var AdminRouter
     */
    private $adminRouter;

    public function __construct(AdminConfig $adminConfig, AdminRouter $adminRouter, ApplicationConfig $applicationConfig, UserRepository $userRepository)
    {
        $this->adminConfig = $adminConfig;
        $this->adminRouter = $adminRouter;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = new RedirectResponse($this->adminRouter->generateUri('admin.login'));

        $sessionData = new SessionData([
            'xsrfToken' => Uuid::uuid4()->toString(),
        ]);
        $sessionCookie = new SessionCookie();
        $response = $sessionCookie->createSessionCookie($request, $response, $this->adminConfig, $sessionData);
        $response = $sessionCookie->createXsrfCookie($request, $response, $sessionData);

        return $response;
    }
}
