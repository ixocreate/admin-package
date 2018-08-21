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

namespace KiwiSuite\Admin\Middleware\Api;

use Firebase\JWT\JWT;
use KiwiSuite\Admin\Entity\SessionData;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SessionDataMiddleware implements MiddlewareInterface
{
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

        if (empty($request->getCookieParams()['kiwiSid'])) {
            return $this->createInvalidSidResponse();
        }

        try {
            $jwt = JWT::decode($request->getCookieParams()['kiwiSid'], 'secret_key', ['HS512']);

            $sessionData = new SessionData((array)$jwt->data);
        } catch (\Throwable $e) {
            return $this->createInvalidSidResponse();
        }

        return $handler->handle($request->withAttribute(SessionData::class, $sessionData));
    }

    private function createInvalidSidResponse(): ApiErrorResponse
    {
        return new ApiErrorResponse('session_invalid', [], 400);
    }
}
