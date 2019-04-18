<?php
declare(strict_types=1);

namespace Ixocreate\Admin\Package;

use Ixocreate\Admin\Package\Type\RoleType;
use Ixocreate\Admin\Package\Type\StatusType;
use Ixocreate\Entity\Package\Type\TypeConfigurator;

/** @var TypeConfigurator $type */

$type->addType(RoleType::class);
$type->addType(StatusType::class);
