<?php
declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Admin\Type\RoleType;
use Ixocreate\Admin\Type\StatusType;
use Ixocreate\Type\TypeConfigurator;

/** @var TypeConfigurator $type */

$type->addType(RoleType::class);
$type->addType(StatusType::class);
