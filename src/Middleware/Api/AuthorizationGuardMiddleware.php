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

use KiwiSuite\Admin\Entity\SessionData;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthorizationGuardMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $sessionData = $request->getAttribute(SessionData::class);
        if (!($sessionData instanceof SessionData)) {
            return $this->createNotAuthorizedResponse();
        }

        if ($sessionData->getUserId() !== 1) {
            return $this->createNotAuthorizedResponse();
        }

        return $handler->handle($request);
    }

    private function createNotAuthorizedResponse(): ApiErrorResponse
    {
        return new ApiErrorResponse('unauthorized', [], 401);
    }
}
