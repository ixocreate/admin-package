<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Type;

use KiwiSuite\Admin\Role\RoleInterface;
use KiwiSuite\Admin\Role\RoleMapping;
use KiwiSuite\Admin\Role\RoleSubManager;
use KiwiSuite\Entity\Type\Convert\Convert;
use KiwiSuite\Entity\Type\TypeInterface;

final class RoleType implements TypeInterface
{
    /**
     * @var RoleInterface
     */
    private $role;

    /**
     * @var RoleSubManager
     */
    private $roleSubManager;

    public function __construct(string $value, RoleSubManager $roleSubManager, RoleMapping $roleMapping)
    {
        $this->roleSubManager = $roleSubManager;

        if (empty($roleMapping->getMapping()[$value])) {
            throw new \Exception("invalid role");
        }

        $roleClass = $roleMapping->getMapping()[$value];

        $this->role = $this->roleSubManager->get($roleClass);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->role::getName();
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function convertToInternalType($value)
    {
        return Convert::convertString($value);
    }

    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return (string)$this;
    }

    public function getRole(): RoleInterface
    {
        return $this->role;
    }
}
