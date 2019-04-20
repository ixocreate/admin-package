<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin;

use Ixocreate\Admin\ImageDefinition\AdminThumb;
use Ixocreate\Media\ImageDefinition\ImageDefinitionConfigurator;

/** @var ImageDefinitionConfigurator $imageDefinition */
$imageDefinition->addImageDefinition(AdminThumb::class);
