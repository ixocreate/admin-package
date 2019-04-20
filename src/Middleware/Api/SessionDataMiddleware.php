<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Middleware\Api;

use Firebase\JWT\JWT;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\SessionData;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SessionDataMiddleware implements MiddlewareInterface
{
    /**
     * @var AdminConfig
     */
    private $adminConfig;

    /**
     * SessionDataMiddleware constructor.
     */
    public function __construct(AdminConfig $adminConfig)
    {
        $this->adminConfig = $adminConfig;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getMethod() === 'OPTIONS') {
            return $handler->handle($request);
        }

        if (empty($request->getCookieParams()['ixoSid'])) {
            return $handler->handle($request);
        }

        try {
            $jwt = JWT::decode($request->getCookieParams()['ixoSid'], $this->adminConfig->secret(), ['HS512']);

            $sessionData = new SessionData((array)$jwt->data);
        } catch (\Throwable $e) {
            return $handler->handle($request);
        }

        return $handler->handle($request->withAttribute(SessionData::class, $sessionData));
    }
}
