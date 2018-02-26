<?php
namespace KiwiSuite\Admin\Role;

final class AdministratorRole implements RoleInterface
{

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'admin';
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return 'Administrator';
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return [
            '*'
        ];
    }
}
