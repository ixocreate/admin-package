<?php
namespace KiwiSuite\Admin\Action\Api\Auth;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use KiwiSuite\Admin\Entity\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

final class UserAction implements MiddlewareInterface
{

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = new User([
            'id' => 1,
            'email' => 'test@kiwi-suite.test',
            'password' => 'test',
        ]);

        return new JsonResponse([
            'success' => true,
            'payload' => $user->toArray(),
        ]);
    }
}
