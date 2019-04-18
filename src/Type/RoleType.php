<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Type;

use Doctrine\DBAL\Types\StringType;
use Ixocreate\Admin\Package\Role\RoleSubManager;
use Ixocreate\Admin\RoleInterface;
use Ixocreate\Schema\Package\BuilderInterface;
use Ixocreate\Schema\Package\ElementInterface;
use Ixocreate\Schema\Package\ElementProviderInterface;
use Ixocreate\Type\Package\DatabaseTypeInterface;
use Ixocreate\Entity\Package\Type\AbstractType;
use Ixocreate\Schema\Package\Elements\SelectElement;

final class RoleType extends AbstractType implements DatabaseTypeInterface, ElementProviderInterface
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

    public function provideElement(BuilderInterface $builder): ElementInterface
    {
        /** @var SelectElement $element */
        $element = $builder->get(SelectElement::class);

        $options = [];
        foreach ($this->roleSubManager->getServices() as $service) {
            /** @var RoleInterface $role */
            $role = $this->roleSubManager->get($service);
            $options[$role::serviceName()] = $role->getLabel();
        }

        return $element->withOptions($options);
    }
}
