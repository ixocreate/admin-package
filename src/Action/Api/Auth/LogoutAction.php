<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Api\Auth;

use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\SessionData;
use Ixocreate\Admin\Response\ApiSuccessResponse;
use Ixocreate\Admin\Session\SessionCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

final class LogoutAction implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * IndexAction constructor.
     * @param AdminConfig $adminConfig
     */
    public function __construct(AdminConfig $adminConfig)
    {
        $this->adminConfig = $adminConfig;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @throws \Exception
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = new ApiSuccessResponse();

        $sessionData = new SessionData([
            'xsrfToken' => Uuid::uuid4()->toString(),
        ]);

        $sessionCookie = new SessionCookie();
        $response = $sessionCookie->createSessionCookie($request, $response, $this->adminConfig, $sessionData);
        $response = $sessionCookie->createXsrfCookie($request, $response, $sessionData);

        return $response;
    }
}
