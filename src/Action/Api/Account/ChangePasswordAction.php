<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Action\Account;

use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Message\ChangePasswordMessage;
use KiwiSuite\Admin\Resource\UserResource;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\Cms\Message\CreatePage;
use KiwiSuite\CommandBus\CommandBus;
use KiwiSuite\CommandBus\Message\MessageSubManager;
use KiwiSuite\Contract\Resource\ResourceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

class ChangePasswordAction implements MiddlewareInterface
{


    /**
     * @var MessageSubManager
     */
    private $messageSubManager;
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(MessageSubManager $messageSubManager, CommandBus $commandBus)
    {
        $this->messageSubManager = $messageSubManager;
        $this->commandBus = $commandBus;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);

        $body = $request->getParsedBody();
        if (empty($body)) {
            $body = [];
        }

        /** @var CreatePage $message */
        $message = $this->messageSubManager->get(ChangePasswordMessage::class);

        $metadata = $routeResult->getMatchedParams();
        if (empty($metadata)) {
            $metadata = [];
        }
        $metadata[User::class] = $request->getAttribute(User::class, null)->id();
        $metadata[ResourceInterface::class] = UserResource::class;

        $message = $message->inject($body, $metadata);
        $result = $message->validate();
        if (!$result->isSuccessful()) {
            return new ApiErrorResponse('invalid.input', $result->getErrors());
        }

        $this->commandBus->handle($message);
        return new ApiSuccessResponse([
            'id' => (string) $message->uuid(),
        ]);
    }
}
