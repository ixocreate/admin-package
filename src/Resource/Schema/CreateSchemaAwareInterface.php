<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\Schema;

use Ixocreate\Admin\UserInterface;
use Ixocreate\Schema\Builder\BuilderInterface;
use Ixocreate\Schema\SchemaInterface;

interface CreateSchemaAwareInterface
{
    public function createSchema(BuilderInterface $builder, UserInterface $user): SchemaInterface;
}
