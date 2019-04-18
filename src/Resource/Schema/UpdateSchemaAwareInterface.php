<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\Schema;

use Ixocreate\Admin\UserInterface;
use Ixocreate\Schema\Package\BuilderInterface;
use Ixocreate\Schema\Package\SchemaInterface;

interface UpdateSchemaAwareInterface
{
    public function updateSchema(BuilderInterface $builder, UserInterface $user): SchemaInterface;
}
