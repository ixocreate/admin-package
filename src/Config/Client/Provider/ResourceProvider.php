<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Config\Client\Provider;

use Ixocreate\Contract\Admin\ClientConfigProviderInterface;
use Ixocreate\Contract\Admin\Resource\Permission\CanCreateInterface;
use Ixocreate\Contract\Admin\Resource\Permission\CanDeleteInterface;
use Ixocreate\Contract\Admin\Resource\Permission\CanEditInterface;
use Ixocreate\Contract\Admin\Resource\Permission\CanViewInterface;
use Ixocreate\Contract\Admin\UserInterface;
use Ixocreate\Contract\Resource\AdditionalSchemasInterface;
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

    public static function serviceName(): string
    {
        return 'resources';
    }

    /**
     * @param UserInterface|null $user
     * @return array
     */
    public function clientConfig(?UserInterface $user = null): array
    {
        if (empty($user)) {
            return [];
        }

        $resources = [];

        foreach ($this->resourceSubManager->getServices() as $service) {
            /** @var ResourceInterface $resource */
            $resource = $this->resourceSubManager->get($service);

            if (!($resource instanceof AdminAwareInterface)) {
                continue;
            }

            $canCreate = true;
            if ($resource instanceof CanCreateInterface) {
                $canCreate = $resource->canCreate($user);
            }
            $canEdit = true;
            if ($resource instanceof CanEditInterface) {
                $canEdit = $resource->canEdit($user);
            }
            $canDelete = true;
            if ($resource instanceof CanDeleteInterface) {
                $canDelete = $resource->canDelete($user);
            }
            $canView = true;
            if ($resource instanceof CanViewInterface) {
                $canView = $resource->canView($user);
            }

            $resourceConfig = [
                'name' => $resource::serviceName(),
                'label' => $resource->label(),
                'listSchema' => $resource->listSchema(),
                'createSchema' => $resource->createSchema($this->builder),
                'updateSchema' => $resource->updateSchema($this->builder),
                'canCreate' => $canCreate,
                'canEdit' => $canEdit,
                'canDelete' => $canDelete,
                'canView' => $canView,
            ];

            if ($resource instanceof AdditionalSchemasInterface && !empty($resource->additionalSchemas($this->builder))) {
                $resourceConfig = \array_merge($resourceConfig, [
                    'additionalSchemas' => $resource->additionalSchemas($this->builder),
                ]);
            }

            $resources[] = $resourceConfig;
        }

        return $resources;
    }
}
