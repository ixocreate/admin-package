<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Entity;

use Ixocreate\Type\Package\Entity\UuidType;
use Ixocreate\Entity\Package\Definition;
use Ixocreate\Entity\Package\DefinitionCollection;
use Ixocreate\Entity\Package\EntityInterface;
use Ixocreate\Entity\Package\EntityTrait;

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

    protected static function createDefinitions() : DefinitionCollection
    {
        return new DefinitionCollection([
            new Definition("userId", UuidType::class, true, true),
            new Definition("xsrfToken", UuidType::class, false, true),
        ]);
    }
}
