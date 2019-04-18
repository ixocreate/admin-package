<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Package\Repository;

use Ixocreate\Admin\Package\Entity\User;
use Ixocreate\Database\Package\Repository\AbstractRepository;

final class UserRepository extends AbstractRepository
{
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return User::class;
    }
}
