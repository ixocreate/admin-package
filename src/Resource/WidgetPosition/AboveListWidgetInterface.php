<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Resource\WidgetPosition;

use Ixocreate\Admin\UserInterface;
use Ixocreate\Admin\Widget\WidgetCollectorInterface;

interface AboveListWidgetInterface
{
    public function receiveAboveListWidgets(UserInterface $user, WidgetCollectorInterface $widgetCollector): void;
}
