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

namespace Ixocreate\Admin\Type;

use Doctrine\DBAL\Types\StringType;
use Ixocreate\Admin\Role\RoleSubManager;
use Ixocreate\Contract\Admin\RoleInterface;
use Ixocreate\Contract\Schema\ElementInterface;
use Ixocreate\Contract\Type\DatabaseTypeInterface;
use Ixocreate\Contract\Type\SchemaElementInterface;
use Ixocreate\Entity\Type\AbstractType;
use Ixocreate\Schema\Elements\SelectElement;
use Ixocreate\Schema\ElementSubManager;

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
