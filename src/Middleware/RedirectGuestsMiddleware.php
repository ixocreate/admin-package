<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Middleware;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Router\AdminRouter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;

class RedirectGuestsMiddleware implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    protected $adminConfig;

    /**
     * @var AdminRouter
     */
    protected $adminRouter;

    public function __construct(AdminConfig $adminConfig, AdminRouter $adminRouter)
    {
        $this->adminConfig = $adminConfig;
        $this->adminRouter = $adminRouter;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * run user check on each request
         */
        $user = $request->getAttribute(User::class);
        if ($user === null) {
            $currentUri = (string)$request->getUri()->withPath($this->adminConfig->uri()->getPath() . $request->getUri()->getPath());
            $loginRoute = $this->adminRouter->generateUri('admin.login');
            if ($currentUri !== $loginRoute) {
                return new RedirectResponse($loginRoute);
            }
        }

        return $handler->handle($request);
    }
}
