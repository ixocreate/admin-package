<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Config\Client\Provider;

use Ixocreate\Admin\ClientConfigProviderInterface;
use Ixocreate\Admin\Resource\AdditionalSchemasInterface;
use Ixocreate\Admin\Resource\Permission\CanCreateInterface;
use Ixocreate\Admin\Resource\Permission\CanDeleteInterface;
use Ixocreate\Admin\Resource\Permission\CanEditInterface;
use Ixocreate\Admin\Resource\Permission\CanViewInterface;
use Ixocreate\Admin\Resource\Schema\CreateSchemaAwareInterface;
use Ixocreate\Admin\Resource\Schema\ListSchemaAwareInterface;
use Ixocreate\Admin\Resource\Schema\UpdateSchemaAwareInterface;
use Ixocreate\Admin\UserInterface;
use Ixocreate\Resource\ResourceInterface;
use Ixocreate\Resource\ResourceSubManager;
use Ixocreate\Schema\Builder\BuilderInterface;
use Ixocreate\Schema\ListSchema\ListSchema;
use Ixocreate\Schema\Schema;
use Ixocreate\Schema\SchemaAwareInterface;

final class ResourceProvider implements ClientConfigProviderInterface
{
    /**
     * @var ResourceSubManager
     */
    private $resourceSubManager;

    /**
     * @var BuilderInterface
     */
    private $builder;

    public function __construct(ResourceSubManager $resourceSubManager, BuilderInterface $builder)
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
