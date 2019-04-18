<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Action\Api\User;

use Ixocreate\Admin\Package\Repository\UserRepository;
use Ixocreate\Admin\Package\Response\ApiErrorResponse;
use Ixocreate\Admin\Package\Response\ApiSuccessResponse;
use Ixocreate\Entity\Package\Entity\EntityInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DeleteAction implements MiddlewareInterface
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
        /** @var EntityInterface $entity */
        $entity = $this->userRepository->find($request->getAttribute("id"));

        if ($entity === null) {
            return new ApiErrorResponse('admin_user_notfound', 'User not found');
        }

        $entity = $entity->with('deletedAt', new \DateTimeImmutable());
        $this->userRepository->save($entity);

        return new ApiSuccessResponse();
    }
}
