<?php
namespace KiwiSuite\Admin\Middleware\Api;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Admin\Entity\SessionData;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AuthorizationGuardMiddleware implements MiddlewareInterface
{

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
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

    private function createNotAuthorizedResponse() : ApiErrorResponse
    {
        return new ApiErrorResponse('auth.not-authorized', [], 401);
    }
}
