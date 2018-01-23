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

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\Exception\MissingResponseException;

final class ErrorMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        \set_error_handler($this->createErrorHandler());

        try {
            $response = $handler->handle($request);

            if (! $response instanceof ResponseInterface) {
                throw new MissingResponseException('Application did not return a response');
            }
        } catch (\Throwable $e) {
            $response = $this->handleThrowable($e, $request);
        }

        \restore_error_handler();

        return $response;
    }

    private function handleThrowable(\Throwable $e, ServerRequestInterface $request) : ResponseInterface
    {
        // TODO: only expose error message in local env
        return new ApiErrorResponse("server-error", [$e->getMessage()], 500);
    }

    private function createErrorHandler() : callable
    {
        return function (int $errno, string $errstr, string $errfile, int $errline) : void {
            if (! (\error_reporting() & $errno)) {
                return;
            }
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        };
    }
}
