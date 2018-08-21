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

namespace KiwiSuite\Admin\Middleware\Api;

use KiwiSuite\CommandBus\Message\MessageInterface;
use KiwiSuite\CommandBus\Message\MessageSubManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Router\RouteResult;

final class MessageInjectorMiddleware implements MiddlewareInterface
{
    /**
     * @var MessageSubManager
     */
    private $messageSubManager;

    /**
     * ResourceInjectorMiddleware constructor.
     * @param MessageSubManager $messageSubManager
     */
    public function __construct(MessageSubManager $messageSubManager)
    {
        $this->messageSubManager = $messageSubManager;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @throws \Exception
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);

        $messageName = $request->getAttribute(MessageInterface::class);
        if (!empty($messageName)) {
            if (\is_string($messageName)) {
                /** @var MessageInterface $message */
                $message = $this->messageSubManager->build($messageName);
            } else {
                $message = $messageName;
            }

            if (!($message instanceof MessageInterface)) {
                //TODO Exception
                throw new \Exception("invalid exception");
            }

            $request = $request->withAttribute(MessageInterface::class, $message);
        } elseif (!empty($routeResult->getMatchedRoute()->getOptions()[MessageInterface::class])) {
            /** @var MessageInterface $message */
            $message = $this->messageSubManager->build($routeResult->getMatchedRoute()->getOptions()[MessageInterface::class]);

            $request = $request->withAttribute(MessageInterface::class, $message);
        }

        return $handler->handle($request);
    }
}
