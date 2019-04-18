<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Action\Api\User;

use Ixocreate\Package\Admin\Entity\User;
use Ixocreate\Package\Admin\Repository\UserRepository;
use Ixocreate\Package\Admin\Response\ApiErrorResponse;
use Ixocreate\Package\Admin\Response\ApiSuccessResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DetailAction implements MiddlewareInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var User $entity */
        $entity = $this->userRepository->find($request->getAttribute("id"));

        if ($entity === null || $entity->deletedAt() !== null) {
            return new ApiErrorResponse('admin_user_notfound', 'User not found');
        }

        return new ApiSuccessResponse([
            'item' => $entity->toPublicArray(),
        ]);
    }
}
