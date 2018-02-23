<?php
namespace KiwiSuite\Admin\Action\Handler;

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

    public function __construct(MessageSubManager $messageSubManager, CommandBus $commandBus)
    {
        $this->messageSubManager = $messageSubManager;
        $this->commandBus = $commandBus;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);
        $options = $routeResult->getMatchedRoute()->getOptions();
        if (empty($options[MessageInterface::class])) {
            throw new \Exception("invalid message");
        }

        /** @var MessageInterface $message */
        $message = $this->messageSubManager->build($options[MessageInterface::class]);

        $body = $request->getParsedBody();
        if (empty($body)) {
            $body = [];
        }

        $metadata = $routeResult->getMatchedParams();
        if (empty($metadata)) {
            $metadata = null;
        }

        $message = $message->inject($body, $metadata);

        $result = $message->validate();
        if (!$result->isSuccessful()) {
            return new ApiErrorResponse('invalid.input', $result->getErrors());
        }

        $this->commandBus->handle($message);

        return new ApiSuccessResponse();
    }
}
