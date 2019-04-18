<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Action\Api\User;

use Ixocreate\Admin\Package\Command\User\UpdateUserCommand;
use Ixocreate\Admin\Package\Entity\User;
use Ixocreate\Admin\Package\Repository\UserRepository;
use Ixocreate\Admin\Package\Response\ApiErrorResponse;
use Ixocreate\Admin\Package\Response\ApiSuccessResponse;
use Ixocreate\CommandBus\CommandBus;
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
    ) {
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
