<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Action\Auth;

use Exception;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Helper\GoogleAuthHelper;
use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Uri\ApplicationUri;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GoogleAuthStartAction implements MiddlewareInterface {
    private AdminConfig $adminConfig;
    private ApplicationConfig $applicationConfig;
    private ApplicationUri $applicationUri;

    public function __construct(AdminConfig $adminConfig, ApplicationConfig $applicationConfig, ApplicationUri $applicationUri) {
        $this->adminConfig = $adminConfig;
        $this->applicationConfig = $applicationConfig;
        $this->applicationUri = $applicationUri;
    }

    /**
     * @throws Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $googleHelper = new GoogleAuthHelper($this->applicationConfig, $this->adminConfig, $this->applicationUri);

        if ($googleHelper->isAllowed()) {
            return new RedirectResponse($googleHelper->getClient()->createAuthUrl());
        }

        throw new Exception('not allowed');
    }
}
