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

namespace KiwiSuite\Admin\Action\Api\User;

use KiwiSuite\Admin\Command\User\UpdateUserCommand;
use KiwiSuite\Admin\Entity\User;
use KiwiSuite\Admin\Repository\UserRepository;
use KiwiSuite\Admin\Response\ApiErrorResponse;
use KiwiSuite\Admin\Response\ApiSuccessResponse;
use KiwiSuite\CommandBus\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UpdateAction implements MiddlewareInterface
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * UpdateAction constructor.
     * @param UserRepository $userRepository
     * @param CommandBus $commandBus
     */
    public function __construct(
        UserRepository $userRepository,
        CommandBus $commandBus
    )
    {
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $data['userId'] = $request->getAttribute('id');

        /** @var User $entity */
        $entity = $this->userRepository->find($data['userId']);

        if ($entity === null || $entity->deletedAt() !== null) {
            return new ApiErrorResponse('admin_user_notfound', 'User not found');
        }

        $result = $this->commandBus->command(UpdateUserCommand::class, $data);
        if ($result->isSuccessful()) {
            return new ApiSuccessResponse();
        }

        return new ApiErrorResponse('execution_error', $result->messages());
    }
}
