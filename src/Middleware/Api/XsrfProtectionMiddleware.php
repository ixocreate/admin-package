<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Middleware\Api;

use Ixocreate\Admin\Entity\SessionData;
use Ixocreate\Admin\Response\ApiErrorResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class XsrfProtectionMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (\in_array($request->getMethod(), ['HEAD', 'OPTIONS', 'GET'])) {
            return $handler->handle($request);
        }

        if (empty($request->getCookieParams()['XSRF-TOKEN'])) {
            return $this->createInvalidXsrfTokenResponse();
        }

        if (!$request->hasHeader('X-XSRF-TOKEN')) {
            return $this->createInvalidXsrfTokenResponse();
        }

        $xsrfToken = \implode("", $request->getHeader("X-XSRF-TOKEN"));

        if ($request->getCookieParams()['XSRF-TOKEN'] !== $xsrfToken) {
            return $this->createInvalidXsrfTokenResponse();
        }

        $sessionData = $request->getAttribute(SessionData::class);
        if (!($sessionData instanceof SessionData)) {
            return $this->createInvalidXsrfTokenResponse();
        }

        if ((string) $sessionData->xsrfToken()->getValue() !== $xsrfToken) {
            return $this->createInvalidXsrfTokenResponse();
        }

        return $handler->handle($request);
    }

    private function createInvalidXsrfTokenResponse(): ApiErrorResponse
    {
        return new ApiErrorResponse("invalid_xsrf", [], 400);
    }
}
