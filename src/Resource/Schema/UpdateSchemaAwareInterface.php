<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\Schema;

use Ixocreate\Admin\UserInterface;
use Ixocreate\Package\Schema\BuilderInterface;
use Ixocreate\Package\Schema\SchemaInterface;

interface UpdateSchemaAwareInterface
{
    public function updateSchema(BuilderInterface $builder, UserInterface $user): SchemaInterface;
}
