<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Action\Account;

use Ixocreate\Admin\Package\Command\Account\ChangeLocaleCommand;
use Ixocreate\Admin\Package\Entity\User;
use Ixocreate\Admin\Package\Response\ApiErrorResponse;
use Ixocreate\Admin\Package\Response\ApiSuccessResponse;
use Ixocreate\CommandBus\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ChangeLocaleAction implements MiddlewareInterface
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        if (empty($data)) {
            $data = [];
        }
        $data['userId'] = $request->getAttribute(User::class, null)->id();

        $result = $this->commandBus->command(ChangeLocaleCommand::class, $data);
        if ($result->isSuccessful()) {
            return new ApiSuccessResponse();
        }

        return new ApiErrorResponse('execution_error', $result->messages());
    }
}
