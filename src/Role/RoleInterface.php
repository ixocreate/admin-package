<?php
namespace KiwiSuite\Admin\Role;

interface RoleInterface
{
    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return array
     */
    public function getPermissions(): array;
}
