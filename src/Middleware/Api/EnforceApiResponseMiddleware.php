<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Middleware\Api;

use Ixocreate\Package\Admin\Response\ApiErrorResponse;
use Ixocreate\Package\Admin\Response\ApiSuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class EnforceApiResponseMiddleware implements MiddlewareInterface
{
    /**
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

        if (!($response instanceof ApiErrorResponse) && !($response instanceof ApiSuccessResponse)) {
            $response = new ApiErrorResponse("bad_request", [], 400);
        }

        return $response;
    }
}
