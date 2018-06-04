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

namespace KiwiSuite\Admin\Action\Handler;

use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Resource\ResourceInterface;
use KiwiSuite\Admin\Resource\ResourceSubManager;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\CommandBus\CommandBus;
use KiwiSuite\CommandBus\Message\MessageInterface;
use KiwiSuite\CommandBus\Message\MessageSubManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

final class HandlerAction implements MiddlewareInterface
{

    /**
     * @var MessageSubManager
     */
    private $messageSubManager;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;

    public function __construct(
        MessageSubManager $messageSubManager,
        CommandBus $commandBus,
        ResourceSubManager $resourceSubManager
    ) {
        $this->messageSubManager = $messageSubManager;
        $this->commandBus = $commandBus;
        $this->resourceSubManager = $resourceSubManager;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);

        /** @var MessageInterface $message */
        $message = $request->getAttribute(MessageInterface::class);

        $body = $request->getParsedBody();
        if (empty($body)) {
            $body = [];
        }

        $metadata = $routeResult->getMatchedParams();
        if (empty($metadata)) {
            $metadata = [];
        }

        $metadata[User::class] = $request->getAttribute(User::class, null);
        if (!empty($metadata[User::class])) {
            $metadata[User::class] = $metadata[User::class]->id();
        }

        if ($request->getAttribute(ResourceInterface::class)) {
            $metadata[ResourceInterface::class] = \get_class($request->getAttribute(ResourceInterface::class));
            $metadata['id'] = $request->getAttribute('id', null);
        }

        $message = $message->inject($body, $metadata);

        $result = $message->validate();
        if (!$result->isSuccessful()) {
            return new ApiErrorResponse('invalid.input', $result->getErrors());
        }

        $this->commandBus->handle($message);

        return new ApiSuccessResponse([
            'id' => (string) $message->uuid()
        ]);
    }
}
