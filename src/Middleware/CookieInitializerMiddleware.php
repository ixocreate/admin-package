<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Middleware;

use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Firebase\JWT\JWT;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Admin\Entity\SessionData;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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

        if (!empty($request->getCookieParams()['kiwiSid']) && !empty($request->getCookieParams()['XSRF-TOKEN'])) {
            return $response;
        }

        $sessionData = new SessionData([
            'xsrfToken' => Uuid::uuid4()->toString(),
        ]);

        $response = $this->writeSessionCookie($request, $response, $sessionData);
        $response = $this->writeXsrfCookie($request, $response, $sessionData);

        return $response;
    }

    private function writeSessionCookie(ServerRequestInterface $request, ResponseInterface $response, SessionData $sessionData) : ResponseInterface
    {
        $jwt = JWT::encode(
            [
                'iat'  => \time(),
                'jti'  => \base64_encode(\random_bytes(32)),
                'iss'  => $request->getUri()->getHost(),
                'nbf'  => \time(),
                'exp'  => \time() + 36000,
                'data' => $sessionData->toArray(),
            ],
            'secret_key',
            'HS512'
        );

        $cookie = SetCookie::create("kiwiSid")
            ->withPath("/")
            ->withValue($jwt)
            ->withDomain($request->getUri()->getHost())
            ->withHttpOnly(true)
            ->withExpires(\time() + 36000)
            ->withSecure(($request->getUri()->getScheme() === "https"));

        return FigResponseCookies::set($response, $cookie);
    }

    private function writeXsrfCookie(ServerRequestInterface $request, ResponseInterface $response, SessionData $sessionData) : ResponseInterface
    {
        $cookie = SetCookie::create("XSRF-TOKEN")
            ->withPath("/")
            ->withValue($sessionData->getXsrfToken())
            ->withDomain($request->getUri()->getHost())
            ->withHttpOnly(false)
            ->withExpires(\time() + 36000)
            ->withSecure(($request->getUri()->getScheme() === "https"));

        return FigResponseCookies::set($response, $cookie);
    }
}
