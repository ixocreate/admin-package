<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Type;

use Doctrine\DBAL\Types\StringType;
use KiwiSuite\Admin\Role\RoleSubManager;
use KiwiSuite\Contract\Admin\RoleInterface;
use KiwiSuite\Contract\Schema\ElementInterface;
use KiwiSuite\Contract\Type\DatabaseTypeInterface;
use KiwiSuite\Contract\Type\SchemaElementInterface;
use KiwiSuite\Entity\Type\AbstractType;
use KiwiSuite\Schema\Elements\SelectElement;
use KiwiSuite\Schema\ElementSubManager;

final class RoleType extends AbstractType implements DatabaseTypeInterface, SchemaElementInterface
{
    /**
     * @var RoleSubManager
     */
    private $roleSubManager;

    public function __construct(RoleSubManager $roleSubManager)
    {
        $this->roleSubManager = $roleSubManager;
    }

    public function transform($value)
    {
        return $this->roleSubManager->get($value);
    }

    public function getRole(): RoleInterface
    {
        return $this->value();
    }

    public function __toString()
    {
        return $this->value()::serviceName();
    }

    public function convertToDatabaseValue()
    {
        return (string) $this;
    }

    public static function baseDatabaseType(): string
    {
        return StringType::class;
    }

    public static function serviceName(): string
    {
        return 'role';
    }

    public function schemaElement(ElementSubManager $elementSubManager): ElementInterface
    {
        /** @var SelectElement $element */
        $element = $elementSubManager->get(SelectElement::class);

        $options = [];
        foreach ($this->roleSubManager->getServices() as $service) {
            /** @var RoleInterface $role */
            $role = $this->roleSubManager->get($service);
            $options[$role::serviceName()] = $role->getLabel();
        }

        return $element->withOptions($options);
    }
}
