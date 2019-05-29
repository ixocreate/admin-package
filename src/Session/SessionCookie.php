<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Session;

use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Firebase\JWT\JWT;
use Ixocreate\Admin\Config\AdminConfig;
use Ixocreate\Admin\Entity\SessionData;
use Ixocreate\Schema\Type\UuidType;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SessionCookie
{
    public function createSessionCookie(ServerRequestInterface $request, ResponseInterface $response, AdminConfig $adminConfig, SessionData $sessionData): ResponseInterface
    {
        $data = [
            'xsrfToken' => (string) $sessionData->xsrfToken()->value(),
        ];

        if ($sessionData->userId() instanceof UuidType) {
            $data['userId'] = $sessionData->userId()->value();
        }

        $jwt = JWT::encode(
            [
                'iat' => \time(),
                'jti' => \base64_encode(\random_bytes(32)),
                'iss' => $request->getUri()->getHost(),
                'nbf' => \time(),
                'exp' => \time() + 31536000,
                'data' => $data,
            ],
            $adminConfig->secret(),
            'HS512'
        );

        $cookie = SetCookie::create('ixoSid')
            ->withValue($jwt)
            ->withPath('/')
            ->withHttpOnly(true)
            ->withSecure(($request->getUri()->getScheme() === 'https'));

        return FigResponseCookies::set($response, $cookie);
    }

    public function createXsrfCookie(ServerRequestInterface $request, ResponseInterface $response, SessionData $sessionData): ResponseInterface
    {
        $cookie = SetCookie::create('XSRF-TOKEN')
            ->withValue($sessionData->xsrfToken()->value())
            ->withPath('/')
            ->withHttpOnly(false)
            ->withSecure(($request->getUri()->getScheme() === 'https'));

        return FigResponseCookies::set($response, $cookie);
    }
}
