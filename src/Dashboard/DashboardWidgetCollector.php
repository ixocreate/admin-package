<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Admin\Dashboard;

use Ixocreate\Contract\Admin\DashboardWidgetCollectorInterface;
use Ixocreate\Contract\Admin\DashboardWidgetInterface;
use Zend\Stdlib\SplPriorityQueue;

final class DashboardWidgetCollector implements DashboardWidgetCollectorInterface
{
    /**
     * @var SplPriorityQueue
     */
    private $queue;

    public function __construct()
    {
        $this->queue = new SplPriorityQueue();
    }

    /**
     * @param DashboardWidgetInterface $dashboardWidget
     */
    public function add(DashboardWidgetInterface $dashboardWidget): void
    {
        $this->queue->insert($dashboardWidget, $dashboardWidget->priority());
    }

    /**
     * @return DashboardWidgetInterface[]
     */
    public function widgets(): array
    {
        if ($this->queue->isEmpty()) {
            return [];
        }

        $this->queue->top();
        return $this->queue->toArray();
    }
}
