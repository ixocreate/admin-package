<?php
namespace KiwiSuite\Admin\Middleware\Api;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Admin\Entity\SessionData;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

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
            return $this->createNotAuthorizedResponse([]);
        }

        if ($sessionData->getUserId() !== 1) {
            return $this->createNotAuthorizedResponse($sessionData->toArray());
        }

        return $handler->handle($request);
    }

    private function createNotAuthorizedResponse(array $result) : JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => 'auth.not-authorized',
            'result' => $result
        ], 401);
    }
}
