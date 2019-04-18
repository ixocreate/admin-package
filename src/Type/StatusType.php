<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Type;

use Doctrine\DBAL\Types\StringType;
use Ixocreate\Schema\Package\BuilderInterface;
use Ixocreate\Schema\Package\ElementInterface;
use Ixocreate\Schema\Package\ElementProviderInterface;
use Ixocreate\Type\Package\DatabaseTypeInterface;
use Ixocreate\Entity\Package\Type\AbstractType;
use Ixocreate\Schema\Package\Elements\SelectElement;

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
