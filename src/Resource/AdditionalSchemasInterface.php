<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource;

use Ixocreate\Admin\UserInterface;
use Ixocreate\Schema\Builder\BuilderInterface;

interface AdditionalSchemasInterface
{
    /**
     * @param BuilderInterface $builder
     * @param UserInterface $user
     * @return null|array
     */
    public function additionalSchemas(BuilderInterface $builder, UserInterface $user): array;
}
