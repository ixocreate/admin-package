<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\Permission;

use Ixocreate\Admin\Package\UserInterface;

interface CanEditInterface
{
    public function canEdit(UserInterface $user): bool;
}
