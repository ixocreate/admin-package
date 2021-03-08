<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Admin\Repository\UserRepository;
use Ixocreate\Database\Repository\RepositoryConfigurator;

/** @var RepositoryConfigurator $repository */
$repository->addRepository(UserRepository::class);
