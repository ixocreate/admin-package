<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Config\Client\Provider;

use Ixocreate\Contract\Admin\ClientConfigProviderInterface;
use Ixocreate\Contract\Admin\RoleInterface;
use Ixocreate\Contract\Resource\AdminAwareInterface;
use Ixocreate\Contract\Resource\ResourceInterface;
use Ixocreate\Resource\SubManager\ResourceSubManager;
use Ixocreate\Schema\Builder;

final class ResourceProvider implements ClientConfigProviderInterface
{
    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;

    /**
     * @var Builder
     */
    private $builder;

    public function __construct(ResourceSubManager $resourceSubManager, Builder $builder)
    {
        $this->resourceSubManager = $resourceSubManager;
        $this->builder = $builder;
    }

    /**
     * @param RoleInterface|null $role
     * @return array
     */
    public function clientConfig(?RoleInterface $role = null): array
    {
        if (empty($role)) {
            return [];
        }

        $resources = [];

        foreach ($this->resourceSubManager->getServices() as $service) {
            /** @var ResourceInterface $resource */
            $resource = $this->resourceSubManager->get($service);

            if (!($resource instanceof AdminAwareInterface)) {
                continue;
            }

            $resources[] = [
                'name' => $resource::serviceName(),
                'label' => $resource->label(),
                'listSchema' => $resource->listSchema(),
                'createSchema' => $resource->createSchema($this->builder),
                'updateSchema' => $resource->updateSchema($this->builder),
                'canCreate' => $resource->canCreate($role),
                'canEdit' => $resource->canEdit($role),
                'canDelete' => $resource->canDelete($role),
                'canView' => $resource->canView($role),
            ];
        }

        return $resources;
    }

    public static function serviceName(): string
    {
        return 'resources';
    }
}
