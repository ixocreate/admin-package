<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package;

use Ixocreate\ServiceManager\NamedServiceInterface;

interface RoleInterface extends NamedServiceInterface
{
    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return array
     */
    public function getPermissions(): array;
}
