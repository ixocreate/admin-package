<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package;

interface UserInterface
{
    /**
     * @return RoleInterface
     */
    public function getRole(): RoleInterface;
}
