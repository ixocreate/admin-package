<?php
declare(strict_types=1);

namespace Ixocreate\Package\Admin;

use Ixocreate\Package\Admin\Type\RoleType;
use Ixocreate\Package\Admin\Type\StatusType;
use Ixocreate\Package\Entity\Type\TypeConfigurator;

/** @var TypeConfigurator $type */

$type->addType(RoleType::class);
$type->addType(StatusType::class);
