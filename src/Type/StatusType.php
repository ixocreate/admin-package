<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Type;

use Doctrine\DBAL\Types\StringType;
use Ixocreate\Package\Schema\BuilderInterface;
use Ixocreate\Package\Schema\ElementInterface;
use Ixocreate\Package\Schema\ElementProviderInterface;
use Ixocreate\Package\Type\DatabaseTypeInterface;
use Ixocreate\Package\Entity\Type\AbstractType;
use Ixocreate\Package\Schema\Elements\SelectElement;

final class StatusType extends AbstractType implements DatabaseTypeInterface, ElementProviderInterface
{
    /**
     * @param $value
     * @throws \Exception
     * @return mixed
     */
    public function transform($value)
    {
        if (!\in_array($value, ['active', 'inactive'])) {
            //TODO Exception
            throw new \Exception("invalid type");
        }

        return $value;
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
        return 'status';
    }

    public function provideElement(BuilderInterface $builder): ElementInterface
    {
        /** @var SelectElement $element */
        $element = $builder->get(SelectElement::class);
        return $element->withOptions([
            'active' => 'Active',
            'inactive' => 'Inactive',
        ]);
    }
}
