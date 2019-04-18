<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Admin\Repository;

use Ixocreate\Package\Admin\Entity\User;
use Ixocreate\Package\Database\Repository\AbstractRepository;

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
