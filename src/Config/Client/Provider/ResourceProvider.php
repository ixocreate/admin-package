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

namespace KiwiSuite\Admin\Config\Client\Provider;

use KiwiSuite\Contract\Admin\ClientConfigProviderInterface;
use KiwiSuite\Contract\Admin\RoleInterface;
use KiwiSuite\Contract\Resource\AdminAwareInterface;
use KiwiSuite\Contract\Resource\ResourceInterface;
use KiwiSuite\Resource\SubManager\ResourceSubManager;
use KiwiSuite\Schema\Builder;

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
            ];
        }

        return $resources;
    }

    public static function serviceName(): string
    {
        return 'resources';
    }
}
