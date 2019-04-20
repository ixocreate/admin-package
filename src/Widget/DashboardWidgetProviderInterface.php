<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Widget;

use Ixocreate\Admin\UserInterface;

interface DashboardWidgetProviderInterface
{
    public function provide(WidgetCollectorInterface $dashboardWidgetCollector, UserInterface $user): void;
}
