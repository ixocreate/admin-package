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

use Doctrine\DBAL\Types\StringType;
use KiwiSuite\Contract\Type\DatabaseTypeInterface;
use KiwiSuite\Entity\Type\AbstractType;

final class StatusType extends AbstractType implements DatabaseTypeInterface
{
    /**
     * @param $value
     * @return mixed
     * @throws \Exception
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
}
