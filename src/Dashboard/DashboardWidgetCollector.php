<?php
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @link https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace KiwiSuite\Admin\Dashboard;

use KiwiSuite\Contract\Admin\DashboardWidgetCollectorInterface;
use KiwiSuite\Contract\Admin\DashboardWidgetInterface;
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
