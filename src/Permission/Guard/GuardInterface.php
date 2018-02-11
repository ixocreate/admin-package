<?php
namespace KiwiSuite\Admin\Permission\Guard;

use KiwiSuite\Admin\Authentication\UserInterface;
use KiwiSuite\Admin\Permission\Role\RoleInterface;

interface GuardInterface
{
    public function can(UserInterface $user, RoleInterface $role) : bool;
}
