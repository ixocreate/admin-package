<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Middleware;

use Firebase\JWT\JWT;
use KiwiSuite\Admin\Entity\SessionData;
use KiwiSuite\Admin\Session\SessionCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

final class CookieInitializerMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if ($request->getMethod() === 'OPTIONS') {
            return $response;
        }

        if (!empty($request->getCookieParams()['kiwiSid'])) {
            try {
                $jwt = JWT::decode($request->getCookieParams()['kiwiSid'], 'secret_key', ['HS512']);

                new SessionData((array)$jwt->data);
            } catch (\Throwable $e) {
                return $this->setSessionData($request, $response);
            }

            return $response;
        }

        return $this->setSessionData($request, $response);
    }

    private function setSessionData(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $sessionData = new SessionData([
            'xsrfToken' => Uuid::uuid4()->toString(),
        ]);

        $sessionCookie = new SessionCookie();
        $response = $sessionCookie->createSessionCookie($request, $response, $sessionData);
        $response = $sessionCookie->createXsrfCookie($request, $response, $sessionData);

        return $response;
    }
}
