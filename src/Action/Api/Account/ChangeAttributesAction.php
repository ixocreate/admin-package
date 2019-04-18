<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Action\Account;

use Ixocreate\Package\Admin\Command\Account\ChangeAttributesCommand;
use Ixocreate\Package\Admin\Entity\User;
use Ixocreate\Package\Admin\Repository\UserRepository;
use Ixocreate\Package\Admin\Response\ApiErrorResponse;
use Ixocreate\Package\Admin\Response\ApiSuccessResponse;
use Ixocreate\CommandBus\CommandBus;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ChangeAttributesAction implements MiddlewareInterface
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
    public function __construct(UserRepository $userRepository, CommandBus $commandBus)
    {
        $this->userRepository = $userRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @throws \Exception
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $data['userId'] = $request->getAttribute(User::class, null)->id();

        /** @var User $entity */
        $entity = $this->userRepository->find($data['userId']);


        if ($entity === null || $entity->deletedAt() !== null) {
            return new ApiErrorResponse('admin_user_notfound', 'User not found');
        }

        $result = $this->commandBus->command(ChangeAttributesCommand::class, $data);
        if ($result->isSuccessful()) {
            return new ApiSuccessResponse();
        }
        return new ApiErrorResponse('execution_error', $result->messages());
    }
}
