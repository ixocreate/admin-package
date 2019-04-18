<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Entity;

use Ixocreate\Package\Type\Entity\UuidType;
use Ixocreate\Package\Entity\Definition;
use Ixocreate\Package\Entity\DefinitionCollection;
use Ixocreate\Package\Entity\EntityInterface;
use Ixocreate\Package\Entity\EntityTrait;

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
