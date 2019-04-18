<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Config\Client\Provider;

use Ixocreate\Admin\Package\ClientConfigProviderInterface;
use Ixocreate\Admin\Resource\AdditionalSchemasInterface;
use Ixocreate\Admin\Resource\Permission\CanCreateInterface;
use Ixocreate\Admin\Resource\Permission\CanDeleteInterface;
use Ixocreate\Admin\Resource\Permission\CanEditInterface;
use Ixocreate\Admin\Resource\Permission\CanViewInterface;
use Ixocreate\Admin\Resource\Schema\CreateSchemaAwareInterface;
use Ixocreate\Admin\Resource\Schema\ListSchemaAwareInterface;
use Ixocreate\Admin\Resource\Schema\UpdateSchemaAwareInterface;
use Ixocreate\Admin\Package\UserInterface;
use Ixocreate\Resource\Package\ResourceInterface;
use Ixocreate\Schema\Package\SchemaAwareInterface;
use Ixocreate\Resource\Package\SubManager\ResourceSubManager;
use Ixocreate\Schema\Package\Builder;
use Ixocreate\Schema\Package\Listing\ListSchema;
use Ixocreate\Schema\Package\Schema;

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
            $canView = false;
            if ($resource instanceof CanViewInterface) {
                $canView = $resource->canView($user);
            }

            $listSchema = new ListSchema();
            if ($resource instanceof ListSchemaAwareInterface) {
                $listSchema = $resource->listSchema($user);
            }

            $createSchema = new Schema();
            if ($resource instanceof CreateSchemaAwareInterface) {
                $createSchema = $resource->createSchema($this->builder, $user);
            } elseif ($resource instanceof SchemaAwareInterface) {
                $createSchema = $resource->schema($this->builder);
            }

            $updateSchema = new Schema();
            if ($resource instanceof UpdateSchemaAwareInterface) {
                $updateSchema = $resource->updateSchema($this->builder, $user);
            } elseif ($resource instanceof SchemaAwareInterface) {
                $updateSchema = $resource->schema($this->builder);
            }

            $resourceConfig = [
                'name' => $resource::serviceName(),
                'label' => $resource->label(),
                'listSchema' => $listSchema,
                'createSchema' => $createSchema,
                'updateSchema' => $updateSchema,
                'canCreate' => $canCreate,
                'canEdit' => $canEdit,
                'canDelete' => $canDelete,
                'canView' => $canView,
                'additionalSchemas' => [],
            ];

            if ($resource instanceof AdditionalSchemasInterface) {
                $resourceConfig['additionalSchemas'] = $resource->additionalSchemas($this->builder, $user);
            }

            $resources[] = $resourceConfig;
        }

        return $resources;
    }
}
