<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Action\Api\Resource;

use Ixocreate\Admin\Entity\User;
use Ixocreate\Admin\Resource\DefaultValueInterface;
use Ixocreate\Admin\Response\ApiDetailResponse;
use Ixocreate\Resource\ResourceSubManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class DefaultValueAction implements MiddlewareInterface
{
    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;

    public function __construct(
        ResourceSubManager $resourceSubManager
    ) {
        $this->resourceSubManager = $resourceSubManager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $resource = $this->resourceSubManager->get($request->getAttribute('resource'));

        $values = [];
        if ($resource instanceof DefaultValueInterface) {
            $values = $resource->defaultValues($request->getAttribute(User::class));
        }

        return new ApiDetailResponse(
            $resource,
            $values,
            []
        );
    }
}
