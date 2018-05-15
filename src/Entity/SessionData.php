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

namespace KiwiSuite\Admin\Entity;

use KiwiSuite\CommonTypes\Entity\UuidType;
use KiwiSuite\Entity\Entity\Definition;
use KiwiSuite\Entity\Entity\DefinitionCollection;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Entity\EntityTrait;

final class SessionData implements EntityInterface
{
    use EntityTrait;

    /**
     * @var
     */
    private $userId;

    /**
     * @var
     */
    private $xsrfToken;

    /**
     * @return UuidType|null
     */
    public function userId():? UuidType
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function xsrfToken() : UuidType
    {
        return $this->xsrfToken;
    }

    protected function createDefinitions() : DefinitionCollection
    {
        return new DefinitionCollection([
            new Definition("userId", UuidType::class, true, true),
            new Definition("xsrfToken", UuidType::class, false, true),
        ]);
    }
}
