<?php
namespace KiwiSuite\Admin\Permission\Role;

final class Role implements RoleInterface
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
