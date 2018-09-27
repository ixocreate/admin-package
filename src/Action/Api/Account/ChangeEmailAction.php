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

use KiwiSuite\Admin\Command\Account\ChangeEmailCommand;
use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\CommandBus\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ChangeEmailAction implements MiddlewareInterface
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

        $result = $this->commandBus->command(ChangeEmailCommand::class, $data);
        if ($result->isSuccessful()) {
            return new ApiSuccessResponse();
        }

        return new ApiErrorResponse('execution_error', $result->messages());
    }
}
