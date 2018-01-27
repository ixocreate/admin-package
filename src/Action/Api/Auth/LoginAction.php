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

namespace KiwiSuite\Admin\Action\Api\Auth;

use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Firebase\JWT\JWT;
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Admin\Entity\SessionData;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

final class LoginAction implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        if (empty($data['email']) || empty($data['password']) || $data['email'] !== 'test@kiwi-suite.test' || $data['password'] !== 'test') {
            return new ApiErrorResponse("invalid_credentials", 401);
        }

        $response = new ApiSuccessResponse();

        $sessionData = new SessionData([
            'xsrfToken' => Uuid::uuid4()->toString(),
            'userId' => 1,
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
                // 'iss'  => 'localhost',
                'nbf'  => \time(),
                'exp'  => \time() + 31536000,
                'data' => $sessionData->toArray(),
            ],
            'secret_key',
            'HS512'
        );

        $cookie = SetCookie::create("kiwiSid")
            ->withValue($jwt)
            ->withPath("/")
            ->withDomain($request->getUri()->getHost())
            // ->withDomain('localhost')
            ->withHttpOnly(true)
            ->withSecure(($request->getUri()->getScheme() === "https"));

        return FigResponseCookies::set($response, $cookie);
    }

    private function writeXsrfCookie(ServerRequestInterface $request, ResponseInterface $response, SessionData $sessionData) : ResponseInterface
    {
        $cookie = SetCookie::create("XSRF-TOKEN")
            ->withValue($sessionData->getXsrfToken())
            ->withPath("/")
            ->withDomain($request->getUri()->getHost())
            // ->withDomain('localhost')
            ->withHttpOnly(false)
            ->withSecure(($request->getUri()->getScheme() === "https"));

        return FigResponseCookies::set($response, $cookie);
    }
}
