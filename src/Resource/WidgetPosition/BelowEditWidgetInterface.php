<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\WidgetPosition;

use Ixocreate\Admin\UserInterface;
use Ixocreate\Admin\Widget\WidgetCollectorInterface;

interface BelowEditWidgetInterface
{
    public function receiveBelowEditWidgets(UserInterface $user, WidgetCollectorInterface $widgetCollector, string $id): void;
}
