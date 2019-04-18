<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\Schema;

use Ixocreate\Admin\Package\UserInterface;
use Ixocreate\Schema\Package\Listing\ListSchemaInterface;

interface ListSchemaAwareInterface
{
    public function listSchema(UserInterface $user): ListSchemaInterface;
}
