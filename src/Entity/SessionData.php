<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Entity;

use Ixocreate\Entity\Definition;
use Ixocreate\Entity\DefinitionCollection;
use Ixocreate\Entity\EntityInterface;
use Ixocreate\Entity\EntityTrait;
use Ixocreate\Schema\Type\UuidType;

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
            new Definition('userId', UuidType::class, true, true),
            new Definition('xsrfToken', UuidType::class, false, true),
        ]);
    }
}
