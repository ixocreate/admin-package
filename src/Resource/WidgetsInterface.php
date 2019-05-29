<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\Widgets;

use Ixocreate\Admin\Resource\WidgetPosition\AboveCreateWidgetInterface;
use Ixocreate\Admin\Resource\WidgetPosition\AboveEditWidgetInterface;
use Ixocreate\Admin\Resource\WidgetPosition\AboveListWidgetInterface;
use Ixocreate\Admin\Resource\WidgetPosition\BelowCreateWidgetInterface;
use Ixocreate\Admin\Resource\WidgetPosition\BelowEditWidgetInterface;
use Ixocreate\Admin\Resource\WidgetPosition\BelowListWidgetInterface;

interface WidgetsInterface extends
    AboveCreateWidgetInterface,
    AboveEditWidgetInterface,
    AboveListWidgetInterface,
    BelowCreateWidgetInterface,
    BelowEditWidgetInterface,
    BelowListWidgetInterface
{
}
