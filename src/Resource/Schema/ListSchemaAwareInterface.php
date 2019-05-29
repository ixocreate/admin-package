<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\Schema;

use Ixocreate\Admin\UserInterface;
use Ixocreate\Schema\ListSchema\ListSchemaInterface;

interface ListSchemaAwareInterface
{
    public function listSchema(UserInterface $user): ListSchemaInterface;
}
