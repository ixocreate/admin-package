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
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Admin\Entity\SessionData;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SessionDataMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (empty($request->getCookieParams()['kiwiSid'])) {
            return $this->createInvalidSidResponse();
        }

        try {
            $jwt = JWT::decode($request->getCookieParams()['kiwiSid'], 'secret_key', ['HS512']);

            $sessionData = new SessionData((array) $jwt->data);
        } catch (\Throwable $e) {
            return $this->createInvalidSidResponse();
        }

        return $handler->handle($request->withAttribute(SessionData::class, $sessionData));
    }

    private function createInvalidSidResponse() : ApiErrorResponse
    {
        return new ApiErrorResponse('session.invalid', [], 406);
    }
}
