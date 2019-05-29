<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Repository;

use Ixocreate\Admin\Entity\User;
use Ixocreate\Database\Repository\AbstractRepository;

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
